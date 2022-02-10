<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use backend\models\Cloudflare;
use backend\models\CloudflareSearch;
use backend\models\Domain;
use backend\models\Menu;
use backend\models\AuthAssignment;

/**
 * CloudflareController implements the CRUD actions for Cloudflare model.
 */
class CloudflareController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
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
     * Lists all Cloudflare models.
     * @return mixed
     */
    public function actionIndex()
    {
		
		$cf_cnt = Cloudflare::find()->count();
		
        $searchModel = new CloudflareSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'count' => $cf_cnt,
        ]);
    }

    /**
     * Displays a single Cloudflare model.
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
     * Creates a new Cloudflare model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cloudflare();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cloudflare model.
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
     * Deletes an existing Cloudflare model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		$dmn = Domain::findOne(['cf_id' => $id]);

		$dmn->cf_id = NULL;
		$dmn->save();
		
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Cloudflare model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cloudflare the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cloudflare::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
