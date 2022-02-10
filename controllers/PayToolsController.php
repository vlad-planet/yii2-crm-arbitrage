<?php
namespace backend\controllers;

use Yii;
use backend\models\PayTools;

class PayToolsController extends \yii\web\Controller
{
    /**
     * Creates a new PayTools model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PayTools();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/dept/index', 'id' => $model->id]);
        }
    }
	
    /**
     * Deletes an existing PayTools model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['/dept']);
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
        if (($model = PayTools::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
