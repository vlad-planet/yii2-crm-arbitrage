<?php

namespace backend\controllers;																	// connect handlers

use Yii;																						// auxiliary classes 
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

use backend\models\Server;																		// db models
use backend\models\Domain;
use backend\models\DomainSearch;
use backend\models\Registrator;
use backend\models\Cloudflare;
use backend\models\Menu;
use backend\models\AuthAssignment;

use common\models\Status;																		// system models
use common\models\IspManager;
use common\models\RegHouse;
use common\models\NameCheap;
use common\models\UploadForm;
use common\models\SshTunnel;
use common\models\CloudApi;
use common\models\Ftp;

/**
 * DomainController implements the CRUD actions for Domain model.
 */
class DomainController extends Controller
{
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
                        'actions' => ['login', 'dirdata','error'], //'error'
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'upload', 'remove', 'folder', 'cloak', 'dirdata'],
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
     * Data about the Domain directory..														<-------<<
     */
	public function actionDirdata($domain)
	{
		$dmn = Domain::findOne(['name' => $domain]);
		if($srv = Server::findOne($dmn->server_id))
		{
			if($srv->type == 1){																// if server vps
				$ssh = new SshTunnel();															// ssh model
				if($ssh->connectServer($srv->ip,$srv->login,$srv->password))
				{
					if($val = $ssh->dirData($dmn->name)){
						$dmn->size = $val['size'];
						$dmn->files = $val['count'];
						
					}else{return false;}
				}else{return false;}
			}
			if($srv->type == 2){																// if hosting
				$ftp = new Ftp();																// ftp model
				if($ftp->connectServer($srv->ip,$srv->login,$srv->password))
				{
					if($val = $ftp->dirData($dmn->name)){
						$dmn->size = $val['size'];
						$dmn->files = $val['count'];
						
					}else{return false;}
				}else{return false;}
			}
			
			if($dmn->save()){																	// save system info domain
				if($this->serverDisc($srv)){													// save system info server
					return true;
				}
			}
		}
		return false;
	}

    /**
     * Server Disk system Informations.															<-------<<
     */
	public function serverDisc($srv)
	{
			$isp = IspManager::getInstance();
			$isp->available($srv['ip'],$srv['login'],$srv['password']);

			if($srv->type == 1){																// if server vps
				$sstm = $isp->systemInfo();
				$srv->disc = $sstm->value_used.'/'.$sstm->value_total;
			}
			if($srv->type == 2){																// if hosting
				$dsk = $isp->diskUsage();
				$srv->disc = $dsk['fullsize'].'/';
			}
			$srv->updated_at = (int)microtime(true);
			if($srv->save()){
				return true;
			}
			return false;
	}

    /**
     *  File Transfer Handler.																	<-------<<
     */
	public function fileTransferHandler($srv,$dmn,$fls)
	{
		if($srv->type == 1){																	// if server vps
			$ssh = new SshTunnel();																// ssh model
			if($ssh->connectServer($srv->ip,$srv->login,$srv->password))
			{
				if($ssh->transferFiles($dmn->name,$fls))										// file transfer via ssh
				{
					if($val = $ssh->dirData($dmn->name)){
						$dmn->size = $val['size'];
						$dmn->files = $val['count'];
					}
					
				}else{return false;}
			}else{return false;}
		}
		if($srv->type == 2){																	// if hosting
			$ftp = new Ftp();																	// ftp model
			if($ftp->connectServer($srv->ip,$srv->login,$srv->password))
			{
				if($ftp->transferFiles($dmn->name,$fls))										// file transfer via ftp
				{
					if($val = $ftp->dirData($dmn->name)){
						$dmn->size = $val['size'];
						$dmn->files = $val['count'];
					}
					
				}else{return false;}
			}else{return false;}
		}
		return true;
	}

    /**
     * Uploads Files Cloak.																		<-------<<
     */
    public function actionCloak()
    {
		if(\Yii::$app->request->isAjax){
			$files = 'cloak.zip';

			$dmn = Domain::findOne(['id' => Yii::$app->request->post('id')]);
			if($srv = Server::getServer($dmn->server_id))
			{
				if(!$this->fileTransferHandler($srv,$dmn,$files)){								// file transfer handler.
					return false;
				}
				if($dmn->save()){
					return 'add cloak to domain '.$dmn->name;
				}
			}
		}
	}

	/**
     * To run FTP SSH Client.																	<-------<<
     */
    public function actionFolder()
    {
		if(\Yii::$app->request->isAjax){
			$srv = Server::getServer(Yii::$app->request->post('id'));
			list($ip, $port) = explode(':',$srv->ip);
			$attr = array(																		// sftp client access
				'ftpserver' => $ip,
				'username' =>  $srv->login,
				'password' =>  $srv->password,
				'type' => $srv->type,
			);
			$attr = json_encode($attr);
			return $attr;
		}
	}

    /**
     * Mass File Upload Handler																	<-------<<
     * @return mixed
     */
    public function actionUpload()
    {
        $model = new UploadForm();

		if($slctn = Yii::$app->request->post('selection')){
			Yii::$app->session['slctn'] = $slctn; 												// array of selected domains
		}
		elseif(Yii::$app->request->isPost){
			
			$slctn = Yii::$app->session['slctn'];
			$dmn = Domain::findOne(['id' => $slctn]);
			
			if ($dmn){
				$dmn->status = STATUS::STATUS_ACTIVE;

				if($srv = Server::getServer($dmn->server_id,STATUS::STATUS_ACTIVE)) 			// server for file transfer
				{
					$model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
					if ($model->upload()) 														// if file is uploaded successfully
					{						
						if($this->fileTransferHandler($srv,$dmn,$model->imageFiles[0]))
						{
							if($dmn->save()){													// save system info domain
								if($this->serverDisc($srv)){									// save system info server
									return true;
								}
							}
						}
					}
				}else{ echo 'Нет доступных серверов'; }
			}
			return false;
		}
        return $this->render('upload', ['model' => $model,]);
    }


    /**
     * Remove File																				<-------<<
     */
    public function actionRemove()
    {
        $model = new UploadForm();
		
        if (Yii::$app->request->isPost){
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
			if ($model->remove()){																// file is removeed successfully
                return true;
            }
        }
        return $this->render('remove', ['model' => $model]);
    }

    /**
     * Lists all Domain models.																	<-------<<
     * @return mixed
     */
    public function actionIndex()
    {
		$nd = [];

		$searchModel = new DomainSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		if($search = Yii::$app->request->post('search')){										// mass search
			$dataProvider = $searchModel->search(null);
			$dmns = explode("\n", str_replace("\r", "", $search));

			$dataProvider->query->where(['IN', 'name', $dmns]);
			foreach($dmns as $name){
				if(!$dmn = Domain::findOne(['name' => $name])){
					$nd[] = $name;
				}
			}
		}

		if(Yii::$app->request->get('server_id')){												// filter domains by server
			$dataProvider->query->where('server_id = '.Yii::$app->request->get('server_id'));
		}
		
		$dataProvider->pagination = ['pageSize' => 50];											// entries count
		if ($ent = Yii::$app->request->get('entriesCount')) {
			$dataProvider->pagination = ['pageSize' => $ent];
        }

        return $this->render('index', [															// index page
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'lack' => $nd,
        ]);
    }

    /**
     * Displays a single Domain model.															<-------<<
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [ 
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Domain model.																<-------<<
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$srv_lmt = 0;
		$dmn_cnt = 0;
		$srvs_act = Server::find()->where(['!=', 'id', 0])->andWhere(['status' => STATUS::STATUS_ACTIVE])->asArray()->all();
		
		foreach($srvs_act as $srv){
			$srv_lmt += $srv['limit'];
			$dmn_cnt += Domain::find()->where(['server_id' =>  $srv['id']])->count();
		}

		$limit = $srv_lmt-$dmn_cnt;																// number of available servers for domains
		$data=[]; $ns = null; $ids=[];
		
		$isp = IspManager::getInstance();														// model object isp manager
        $model = new Domain();
		$model->server_id = 0;
		
		$cf_cnt = Cloudflare::find()->where(['status' => STATUS::STATUS_WAIT])->count();

		if($arr = Yii::$app->request->post()){

			$domains = explode("\n", str_replace("\r", "", $arr["Domain"]["name"]));
			$cd = count($domains);
			
			if($limit < $cd && $arr["Domain"]["cf"] == 0){
				$model->addError('param', 'Превышен лимит доступных для домено слотов'); 		// exceeded the limit servers
				$model->name = $arr["Domain"]["name"];
			}
			if($arr["Domain"]["cf"] == 1){														// calculate the available limit accounts cloud flare
				if($cf_cnt < $cd){
					$cf_cnt = $cd - $cf_cnt;
					$model->addError('param', 'Недостаточно: '. $cf_cnt.' CF аккаентов' );
					$model->name = $arr["Domain"]["name"];
				}																				// select server for cf domain
				if(!$srv = Server::findOne(['status' => STATUS::STATUS_ACTIVE, 'id'=>0])){
					$model->addError('param', 'Сервер для CF аккаентов не добавлен в систему' );
				}

				$ns = "anna.ns.cloudflare.com stan.ns.cloudflare.com";
				$srv_cnt = 0;
			}else{																				// select servers for all domains
				$srvs = Server::find()->where(['status' => STATUS::STATUS_ACTIVE])->andWhere(['!=', 'id', 0])->all();
				$srv_cnt = count($srvs);
			}
			
			if($model->validate(null, false)){
$n = 0;
			for ($i = 0; $i < count($domains); ++$i)
			{
				$domain = trim($domains[$i]);

				if(isset($srvs[$n]))															// calculate the available limit servers
				{
					$srv = $srvs[$n];
					$dmn = Domain::find()->where(['server_id' => [$srv['id']]])->all();
					
					$dmn_cnt = count($dmn);
					
					if($dmn_cnt >= $srv['limit']){												// next if the server reaches the limit
						$n++; $i--;																// send the domain to the next server
						if($srv_cnt <= $n){
							$n = 0;																// run in a circle
						}			continue;
					}
				}

				$reg = Registrator::findOne($arr['Domain']['rgstr']);
				$rgstr = (str_replace(' ', $reg->name, "common\models\ "))::getInstance();

				$model = new Domain();															// create model domain
				$model->name = $domain;
				$model->server_id = $srv['id'];													// linked server
				$model->reg_id = $reg->id;														// linked registrator
				
				if($model->validate(null, false)){
					$res = $rgstr->getDomain($domain);											// ***** adding to registrator
				if($res["Status"] == 'OK')
				{
					$model->status = STATUS::STATUS_INACTIVE;
					$model->end_date = $rgstr->requestStatus($domain);

					if($srv['type'] == 2){
						$ns = $srv['ns'];
					}

					$res = $rgstr->updateDNSDomain($domain,$ns);								// updating the dns in the registrar's system
					
					if($srv['type'] == 1){
						if($res["Status"] == 'OK'){
							list($ip, $port) = explode(':',$srv['ip']);
							$res = $rgstr->addRecord($domain,$ip);								// add an record
						}
					}
																								// ***** adding to cloud flare
				if($res["Status"] == 'OK' && $arr["Domain"]["cf"] == 1)
				{
					$cf = Cloudflare::findOne(['status' => STATUS::STATUS_WAIT]);
					$model->cf_id = $cf->id;
					$capi = new CloudApi($cf->email,$cf->api_key,$cf->account_id);				// connect to account
					
					$res = $capi->userAccount();												// info user account
					if($res["Status"] == 'OK'){
						$acc_id = $res["result"][0]["account"]["id"];
						$res = $capi->createDomain($domain,$acc_id);							// add domain to account
					}
					if($res["Status"] == 'OK'){
						$zode_id = $res["result"]["id"];
						$res = $capi->CompatibilityIPv6($zode_id);								// hide ip
					}
					if($res["Status"] == 'OK'){
						$res = $capi->dnsRecords($zode_id,$ip);									// add an record	
					}
					if($res["Status"] == 'OK'){
						$cf->status = STATUS::STATUS_ACTIVE;
						$cf->save();
					}
				}
					if($res["Status"] == 'OK'){													// ***** adding to isp manager
						$isp->available($srv['ip'],$srv['login'],$srv['password']);
						$res = $isp->addWebDomain($domain);										// add domain to system
					}
					if($res["Status"] == 'OK'){
						$res = $isp->addCertLetsEncrypt($domain);								// request certificate let's enscrypt
						$this->serverDisc($srv);
					}
					if($res["Status"] == 'OK'){
						$model->status = STATUS::STATUS_WAIT;
					}
				}else{
					$model->status = STATUS::STATUS_DELETED;
				}
					$model->error = $res["Status"];
$n++;			}
					if($model->validate() && $model->save()){									// create domain
						$data[] = $model->name.' Completed!';
						$ids[] = $model->id;	
					}
					if($srv_cnt <= $n){$n = 0;}
					$rgstr->logOut();															// close the registrar connection
				}
			}
		}
        return $this->render('create', [														// domain creation page 
            'model' => $model,
			'data' => $data,
			'limit' => $limit,
			'count' => $cf_cnt,
        ]);

    }

    /**
     * Updates an existing Domain model.														<-------<<
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		if($model->status == Yii::$app->request->post('status')){								// status update
			$model->save();
			return true;
		}
        if ($model->load(Yii::$app->request->post()) && $model->save()) {						// save update
            return $this->redirect(['view', 'id' => $model->id]);
        }
		
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Domain model.														<-------<<
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id = null)
    {
		if($id == null){
			$dlt = Yii::$app->request->post('selection');										// mass removing
		}else{
			$dlt[] = $id;
		}
		
		foreach($dlt as $id){																	// removing domains from the system isp manager
			$model = $this->findModel($id);
			$isp = IspManager::getInstance();
			
			if($model->server_id != -1){														
				$srv = Server::findOne(['id' => $model->server_id]);
				$isp->available($srv['ip'],$srv['login'],$srv['password']);
				$isp->deleteWebDomain($model->name);
				$isp->deleteSslCert($model->name);
				
				$this->serverDisc($srv);														// server disk system informations.

				if($model->cf_id != NULL){														// removing domains from the system clod flare
					$cf = Cloudflare::findOne(['id' => $model->cf_id]);
					$capi = new CloudApi($cf->email,$cf->api_key,$cf->account_id);
					$capi->deleteDomain($model->name);
				}
			}
			$model->delete(); //$this->findModel($id)->delete();								// removing domain
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the Domain model based on its primary key value.									<-------<<
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Domain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Domain::findOne($id)) !== null) {
            return $model;																		// return domain model
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}