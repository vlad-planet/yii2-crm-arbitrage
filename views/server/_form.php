<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\Status;
use backend\models\Server;
use backend\models\Hoster;

/* @var $this yii\web\View */
/* @var $model backend\models\Server */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="server-form">

    <?php $form = ActiveForm::begin(); ?>

<?  if(Yii::$app->controller->action->id == 'create'){ ?>

		<?
			if($type == 1){
				echo '<b>Синтаксис добавления:</b><br>';
				echo '1.45.84.11:1500|root|password|hoster<br>';
				echo '1.45.84.11:1500|root|password|hoster<br>';
			}
			if($type == 2){
				echo '<b>Синтаксис добавления:</b><br>';
				echo 'url_panel|user|password|hoster|ns1<br>';
				echo 'ns2#<br>';
				echo 'url_panel|user|password|hoster|ns1<br>';
				echo 'ns2<br>';
				echo 'ns3<br>';
				echo 'ns4#<br>';
			}
		?>

	<?//= $form->errorSummary($model); ?>
    <?= $form->field($model, 'access_srv')->textarea() ?>
	<?= $form->field($model, 'limit')->textInput() ?>
	
	
	<?  if($type == 1){ ?>	
		<?= $form->field($model, 'hoster')->textInput() ?>
	<? } ?>	

<? }else{ ?>

	<?  if($model->type == 1){ ?>
		<?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>
	<? } ?>

	<?  if($model->type == 2){ ?>
		<?= $form->field($model, 'url_panel')->textInput(['maxlength' => true]) ?>
	<? } ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>
	
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'hoster_id')->widget(Select2::classname(), [
			'data' =>  ArrayHelper::map(Hoster::find()->all(), 'id', 'name'),
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);	?>

	<?= $form->field($model, 'type')->widget(Select2::classname(), [
			'data' => Server::TYPES,
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);	?>

    <?//= $form->field($model, 'disc')->textInput() ?>

    <?= $form->field($model, 'limit')->textInput() ?>

	<?  if($model->type == 2){
			echo  $form->field($model, 'ns')->textInput(['maxlength' => true]);
		} ?>

	<?= $form->field($model, 'status')->widget(Select2::classname(), [
			'data' => Status::statusList('Server'),
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);	?>
		
<? } ?>

    <div class="form-group">
        <?= Html::submitButton('Созранить', ['class' => 'btn btn-success btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
