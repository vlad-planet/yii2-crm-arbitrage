<?php

namespace backend\controllers;

use Yii;
use backend\models\Hoster;

class HosterController extends \yii\web\Controller
{
    /**
     * Creates a new Hoster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hoster();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/server/create?type=2', 'type' => 2, 'active' => 2]);
        }
    }
	
    /**
     * Deletes an existing Hoster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['/server/create', 'type' => 2, 'active' => 2]);
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
        if (($model = Hoster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
