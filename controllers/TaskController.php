<?php

namespace backend\controllers;

use Yii;
use backend\models\Task;
use backend\models\TaskSearch;
use backend\models\Domain;
use common\models\Registrator;
use common\models\Satus;
use common\models\RegHouse;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tasklog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$rh = RegHouse::getInstance();
		$blns['RH']['currency'] = '₽';
		$blns['RH']['balance'] = $rh->getBalanceInfo()->data->free;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'balance' => $blns
        ]);
    }


    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {		
		$model = $this->findModel($id);
		$dmns = explode(',',$model->domains);

		$dataProvider = new ActiveDataProvider([
			'query' => Domain::find()->where(['id' => $dmns]),
		]);

        return $this->render('view', [
            'model' => $this->findModel($id),
			'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();
		$ids = [];

        if($model->load(Yii::$app->request->post())){
			
			$model->domains  = explode("\n", $model->domains);

			foreach($model->domains as $domain){
				
				$zone = explode('.', $domain);
				$zone = trim(array_pop($zone));	
				
				if($zone == 'com'){
					$prfx = 'NC';
				}
				if($zone == 'ru'){
					$prfx = 'RH';
				}
				
				$reg = Registrator::findOne(['prefix' => $prfx]);
				
				//$rgstr = (str_replace(' ', $reg->name, "common\models\ "))::getInstance();
				//$rgstr->addDomain($domain, $model->ns);

				$dmn = new Domain();
				$dmn->name = $domain;
				$dmn->a = $model->a;
				$dmn->ns = $model->ns;
				$dmn->reg_id = $reg->id;
				
				$model->status = Satus::STATUS_PROCESS;
				//$model->direction = ;
				
				if($dmn->save()){
					$ids[] = $dmn->id;
				}
			}
			
			$model->domains =  implode(',',$ids);

			if($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
		}
		
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Task model.
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
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
} 