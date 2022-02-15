<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use backend\models\Registrator;
use common\models\Status;

/* @var $this yii\web\View */
/* @var $model backend\models\Domain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="domain-form">
    <?php $form = ActiveForm::begin(); ?>
<?  if(Yii::$app->controller->action->id == 'update'){ ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'server_id')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'reg_id')->widget(Select2::classname(), [
			'data' =>  ArrayHelper::map(Registrator::find()->all(), 'id', 'prefix'),
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);	?>
		
	<?= $form->field($model, 'status')->widget(Select2::classname(), [
			'data' => Status::statusList('Domain'),
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);	?>

<? }else{ ?>

<?
	$rgstr = Registrator::find()->all();
	$arr = [];
	foreach($rgstr as $rgstr){
		$arr[$rgstr->id] = $rgstr->name;
	}
	$model->rgstr = $rgstr->id;
?>
	<?= $form->field($model, 'rgstr')->radioList($arr)->label(false) ?>
	<?= $form->field($model, 'cf')->checkbox() ?>
	<?= $form->errorSummary($model); ?>
    <?= $form->field($model, 'name')->textarea(['value' => $model->name]) ?>

<? } ?>
    <div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
JS;
$this->registerJs($js);
?>