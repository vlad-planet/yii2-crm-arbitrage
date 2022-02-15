<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;

use backend\models\Dept;
use backend\models\PayTools;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="staff-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
		'mask' => '+7(999)999-99-99',
	]);	?>

	<?	//= $form->field($model, 'dept_id')->textInput()
		echo $form->field($model, 'dept_id')->widget(Select2::classname(), [
			'data' =>  ArrayHelper::map(Dept::find()->all(), 'id', 'name'),
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

	<? //= $form->field($model, 'paytools_id')->textInput()
		echo $form->field($model, 'paytools_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map(PayTools::find()->all(), 'id', 'name'),
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

    <?= $form->field($model, 'requisites')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kpi')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'registration_date')->textInput() ?>
	<div class="form-group">
	<? echo '<label>'.$model->attributeLabels()['registration_date'].'</label>'; ?>
	
	<? 
	if($model->registration_date == null){
		$date = date('d-m-Y');
	}else{
		$date = date('d-m-Y', $model->registration_date);
	}
	echo DatePicker::widget([
		'name' => "Staff[registration_date]",
		'value' => $date,
		'pluginOptions' => [
			'startDtae' => date('d-m-Y'),
			'format' => 'dd-mm-yyyy',
			'autoclose'=>true,
		]
	]);	?>
	</div>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	
</div>
