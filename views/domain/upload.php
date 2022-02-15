<?php
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\helpers\Html;
?>

<?
	$form = ActiveForm::begin([
		'options'=>['enctype'=>'multipart/form-data']
	]);

	echo $form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
		'options'=>[
		'multiple' => true,
		 'id' => 'imageFile',
		],
			'pluginOptions' => [
				'uploadUrl' => Url::to(['upload']),
				'deleteUrl' => Url::to(['remove']),					
			]
	]);
?>

<?//= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => '/backend/web/uploads/*']) ?>

<?php ActiveForm::end(); ?>