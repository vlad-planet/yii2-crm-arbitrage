<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\models\Dept;
use backend\models\DeptSearch;
use backend\models\PayTools;
use backend\models\Menu;
use backend\models\AuthAssignment;


/**
 * DeptController implements the CRUD actions for Dept model.
 */
class DeptController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete','pay-tools'],
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
     * Lists all Dept models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeptSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dept model.
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
     * Creates a new Dept model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dept();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
	
    /**
     * Updates an existing Dept model.
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
     * Deletes an existing Dept model.
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
     * Finds the Dept model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dept the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dept::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
