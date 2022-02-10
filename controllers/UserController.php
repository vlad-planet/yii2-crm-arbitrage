<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use backend\models\User;
use backend\models\UserSearch;
use backend\models\Menu;
use backend\models\AuthAssignment;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		/*
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
		*/
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		$model->role = AuthAssignment::findOne(['user_id' => $model->id])->item_name;

        if ($model->load($attr = Yii::$app->request->post()) && $model->save()) {
		
			$sgmt = AuthAssignment::findOne(['user_id' => $id]);
				
			if(isset($attr["User"]["item"])){
				$item = serialize($attr["User"]["item"]);
				$sgmt->item_id = $item;
			}
				$sgmt->item_name = $attr["User"]['role'];
				$sgmt->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
	
		$ass = AuthAssignment::findOne(['user_id' => $id]);
		$ass->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
		
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
