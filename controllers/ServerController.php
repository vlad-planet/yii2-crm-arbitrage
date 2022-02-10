<?php

namespace backend\controllers;																	// connect handlers

use Yii;																						// auxiliary classes 
use yii\web\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use backend\models\Server;																		// db models
use backend\models\ServerSearch;
use backend\models\Domain;
use backend\models\Menu;
use backend\models\AuthAssignment;
use backend\models\Hoster;

use common\models\Status;																		// system models
use common\models\IspManager;

/**
 * ServerController implements the CRUD actions for Server model.
 */
class ServerController extends Controller
{
	protected $server = [];
	
    /**
     * {@inheritdoc} Access Restrictions														<-------<<
     */
    public function behaviors()
    {
		$roles = 'admin';
		if(isset(Yii::$app->user->identity)){													// identification by privilege
			$id = Yii::$app->user->identity->id;
			$sgmnt = AuthAssignment::findOne(['user_id' => $id]);
			$mn = Menu::findOne(['url' => $this->id]);
			
			if($tm = unserialize($sgmnt->item_id)){												// does the user have access
				if($mn && in_array($mn->id, $tm)){
					if($sgmnt->item_name == 'user' && $mn->section_id != Menu::ADMIN){
						$roles = '@';
					}
				}
			}
		}
        return [
            'access' => [																		// page activation
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'monitoring'],
                        'allow' => true,
                        'roles' => [$roles],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Server models.																	<-------<<
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [															// server index page
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Server model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [															// server view page
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a Server Monitoring model.														<-------<<
     * @return $model
     */
	public function actionMonitoring()
	{
		$srvs = Server::findAll(['status' => [STATUS::STATUS_DELETED, STATUS::STATUS_ACTIVE]]);
		foreach($srvs as $srv){
			list($server, $port) = explode(':',$srv->ip); $timeout = 3;
			$fp = @fsockopen ('179.432.157.143', $port, $errno, $errstr, $timeout);				// check if the server is available
			if($fp == false){
				$srv->status = STATUS::STATUS_DELETED;
			}else{
				$srv->status = STATUS::STATUS_ACTIVE;
			}
			$srv->save();
		}
	}

    /**
     * Displays a accessServer model.															<-------<<
     * @return $model
     */
	public function accessServer($model,$view=false)
	{
		$isp = IspManager::getInstance();														// creating isp manager model
		
		list($server, $port) = explode(':',$model->ip); $timeout = 3;
		if($fp = @fsockopen ($server, $port, $errno, $errstr, $timeout)){ 						// check if the server is available

			$isp->available($model->ip,$model->login,$model->password);
			
			if($model->type == 1){																// if server
				
				$res = $isp->editUser('admin',$view);											// add a user to isp manager
				if($res["Status"] == 'OK')
				{
					$sstm = $isp->systemInfo();													// system information about the isp manager server
					$model->disc = $sstm->value_used.'/'.$sstm->value_total;					// using the server disk
					$lcns = $isp->licenseInfo();
					$model->socket = (int)$lcns["webdomain_license_limit"];						// available server sockets
				}else{
					$model->status = STATUS::STATUS_INACTIVE;
				}
			}
			if($model->type == 2){																// if hostin
				
				$res = $isp->diskUsage();														// system information about the isp manager server disk
				if($res["Status"] == 'OK'){	
					$model->disc = $res['fullsize'].'/';										// using the hosting disk
				}else{
					$model->status = STATUS::STATUS_INACTIVE;
				}
			}
			$model->error = $res["Status"];														// response status
		}else{
			$model->error = 'System: Сервер в сети не найден';
			$model->status = STATUS::STATUS_DELETED;
		}
		
		return $model;
	}



    /**
     * Detect IP to Url.																		<-------<<								
     * @return string
     */

	 public function detectIp($url):string
	 {
		$url = trim($url);
		if(strpos($url, 'http') !== false){
			$ip = gethostbyname(parse_url($url)['host']);										// determine ip by url
							
			if($ip != $url){
				$ip = $ip.':1500';
			}else{
				//$ip = '00.00.00.00:0000';
			}
		}
		return $ip;
	 }


    /**
     * Creates a new Server model.																<-------<<	
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type,$active=null)
    {
		$data = [];
		$model = new Server(['scenario' => (int)$type]);

		if($arr = Yii::$app->request->post()){

			if(isset($arr["only"])){															// group addition of hostings 

				foreach($arr["Server"] as $server){

					$model = new Server();
					$model->type = $type;
					$model->limit = $server['limit'];
					
					$model->access_srv = $server["url_panel"];

					list($model->url_panel,$model->login,$model->password) = explode('|',$model->access_srv);
					
					$model->ns = str_replace("\n", " ", $server['ns']);
					$model->hoster_id = $server["hoster_id"];

					$model->ip = $this->detectIp($model->url_panel);							// detect ip						

					$model->status = STATUS::STATUS_ACTIVE;
					$model = $this->accessServer($model);										// system information about the server

					if($model->validate() && $model->save()){
						$data[] = $model->ip.' Completed!';
					}
				}
				
			}else{																				// mass addition of hostings and servers
				
				if($type == 1){																	// if server
					$field = 'ip';
					$s = "\n";
				}
				if($type == 2){																	// if hosting
					$active = 1;
					$field = 'url_panel';
					$s = "#";
				}

				$access_srv = array_filter( explode( $s, str_replace( "\r", "", $arr["Server"]["access_srv"] ) ) );

				foreach($access_srv as $access){
					//$access = preg_replace("/\s+/", "", $access);
					
					$model = new Server();
					$model->type  = $type;
					$model->limit = $arr["Server"]['limit'];

					list($model->$field,$model->login,$model->password,$hoster,$model->ns) = array_pad(explode('|',$access), 5, ' ');

					$model->ns = str_replace("\n", " ", $model->ns);
///
					if(isset($arr["Server"]["hoster"]) && $arr["Server"]["hoster"] != null){
						$hoster = $arr["Server"]["hoster"];
					}
///

					$hoster = mb_strtolower($hoster);					
					if(!$hstr = Hoster::findOne(['name' => $hoster])){							// adding or choosing a hoster
						$hstr = new Hoster();
						$hstr->name = $hoster;
						$hstr->save();
					}
					$model->hoster_id = $hstr->id;
					

					if($model->type == 2){														// if hosting		
						$model->ip = $this->detectIp($model->url_panel);						// detect ip
					}

					$model->status = STATUS::STATUS_ACTIVE;
					$model = $this->accessServer($model);										// system information about the server
					
					if($model->validate() && $model->save()){
						$data[] = $model->ip.' Completed!';
					}
				}
			}
		}

		$models = [new Server(['scenario' => (int)$type])];
		
        return $this->render('create',[															// server create page
            'model' => $model,
			'models' => (empty($models)) ? [new Server] : $models,
			'data' => $data,
			'type' => $type,
			'active' => $active,
        ]);

    }

    /**
     * Updates an existing Server model.														<-------<<
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())){
			$model = $this->accessServer($model,true);											// system information about the server
			
			if($model->save()){
				 return $this->redirect(['view', 'id' => $model->id]);
			}
        }
        return $this->render('update', [														// server update page
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Server model.														<-------<<
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id = null)
    {
		if($id == null){
			$dlt = Yii::$app->request->post('selection');										// list of selected servers
		}else{
			$dlt[] = $id;
		}
		
		foreach($dlt as $id){																	// list of servers to be deleted
			
			$model = $this->findModel($id);

			list($server, $port) = explode(':',$model['ip']); $timeout = 3;
			$fp = @fsockopen ($server, $port, $errno, $errstr, $timeout); 						// check if the server is available

			$isp = IspManager::getInstance();
			$isp->available($model['ip'],$model['login'],$model['password']);

			if ($fp && $model['type'] == 1) {													// if server delete all domains this user
				$isp->deleteUser('admin');
			}

			$dmns = Domain::findAll(['server_id' => $id]);										// select all attached domains this server
			foreach($dmns  as $dmn){
				
				if($fp && $model['type'] == 2){													// if hosting
					$isp->deleteWebDomain($dmn->name);											// delete attached domain in system isp manager
					$isp->deleteSslCert($dmn->name);											// delete attached certificates in system isp manager
				}
				
				$dmn->status = STATUS::STATUS_INACTIVE;											// deactivate domain
				$dmn->error .= ", Infinitum:\n server deleted";
				$dmn->server_id = -1;															// unpin a domain in system
				$dmn->save();
			}
			$model->delete();	// $this->findModel($id)->delete();								// remove server from system
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the Server model based on its primary key value.									<-------<<
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Server the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Server::findOne($id)) !== null) {
            return $model;																		// return server model
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}