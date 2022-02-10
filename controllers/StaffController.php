<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Json;
use yii\helpers\Url;

use backend\models\Staff;
use backend\models\StaffLog;
use backend\models\StaffSearch;
use backend\models\Menu;
use backend\models\AuthAssignment;
use backend\models\Dept;
use backend\models\PayTools;

use common\models\UploadForm;
use common\models\Status;


/**
 * StaffController implements the CRUD actions for Staff model.
 */
class StaffController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
		$roles = 'admin';
		if(isset(Yii::$app->user->identity)){
			$id = Yii::$app->user->identity->id;
			$sgmnt = AuthAssignment::findOne(['user_id' => $id]);
			$mn = Menu::findOne(['url' => $this->id]);
			
			if($tm = unserialize($sgmnt->item_id)){
				if($mn && in_array($mn->id, $tm)){
					if($sgmnt->item_name == 'user' && $mn->section_id != Menu::ADMIN){
						$roles = '@';
					}
				}
			}
		}
        return [
		    'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'upload', 'remove', 'docs', 'dismissal'],
                        'allow' => true,
                        'roles' => ['admin'],
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
     * Lists all Staff models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Staff model.
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
     * Creates a new Staff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
	 
    public function actionCreate()
    {
        $model = new Staff();

        if ($model->load(Yii::$app->request->post())) {
            $model->registration_date = strtotime(Yii::$app->request->post('Staff')['registration_date']);
			
			if($model->save()){
				return $this->render('docs', ['model' => $model]);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
	
    /**
     * Upload Staff Docs.
     */
	
	public function actionDocs()
    {
        $model = new Staff();

        return $this->render('docs', [
            'model' => $model,
        ]);
    }
	 
    /**
     * Deletes an existing Staff model.
     * If deletion is successful, the browser will be redirected to the 'upload' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpload($id)
    {
        $model = new UploadForm();
		$model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

		$url = \Yii::getAlias('@upload').'/'.$id;
		if(!is_dir($url)) {
			mkdir($url, 0777);
		}
        if($model->upload($id)) // file is uploaded successfully
		{
			return Json::encode([
				'initialPreview' =>  '<img src="/backend/web/upload/'.$id.'/'.$model->imageFiles[0].'">',
				'initialPreviewConfig' => [
					[
						'url'=>Url::to(['remove?id='.$id.'&file='.$model->imageFiles[0]]),
					],
				],
			]);
        }
		return false;
    }
	
	
    public function actionRemove($id,$file)
    {
		$url = \Yii::getAlias('@upload').'/'.$id.'/'.$file;
		
		if(unlink($url)){
			return true;
		}
		return false;
    }

    /**
     * Updates an existing Staff model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$file=null)
    {
		
        $model = $this->findModel($id);
		$data = clone($model);

        if ($model->load(Yii::$app->request->post())) {
			
			$attr = $model->getDirtyAttributes();
			//var_dump(Yii::$app->request->post());

			$model->registration_date = strtotime(Yii::$app->request->post('Staff')['registration_date']);
			
			if($model->save()){
				$stff = new Staff();

				foreach($attr as $key => $val){
					
					$log = new StaffLog();
					$log->staff_id = $id;
					$log->name = $stff->getAttributeLabel($key);



					$log->value = $data->$key;
					
					if($key  == 'registration_date' && $model->registration_date != $data->$key){
						$log->value = $val;
					}
					elseif($key == 'dept_id' && $val != $data->$key){
						$log->value = Dept::findOne(['id' => $data->$key])->name;
					}
					elseif($key == 'paytools_id' && $val != $data->$key)
					{
						$log->value = PayTools::findOne(['id' => $data->$key])->name;
					}
					elseif($key == 'salary' && $val != $data->$key)
					{
						$log->value = (string)$data->$key;
					}
					elseif($key == 'kpi' && $val != $data->$key)
					{
						$log->value = (string)$data->$key;
					}
					$log->save();
				}
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }
		
		if(!is_null($file)){
			$this->actionRemove($id,$file);
		}
		
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Staff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
	
    /**
	 * Dismissal an existing Staff model.
     * @return mixed
     */
    public function actionDismissal($id)
    {
		$stff = $this->findModel($id);		
		$stff->status = -1;
		$stff->dismissal_date = (int)microtime(true);
		$stff->save();
		
        return $this->redirect(['index']);
    }
	
    /**
     * Finds the Staff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Staff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Staff::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
