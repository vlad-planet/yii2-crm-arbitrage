<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use backend\models\Menu;
use backend\models\AuthAssignment;
use backend\models\AuthItem;
use backend\models\Dept;

use common\models\Status;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
	
	<?
	$sgmnt = AuthAssignment::findOne(['user_id' => $model->id]);
	if($sgmnt->item_name == 'user'){
		$mn = Menu::find()->all();
		foreach($mn as $mn){
			$ck = false;
			if($sgmnt->item_id && in_array($mn->id, unserialize($sgmnt->item_id))){
				$ck = true;
			}
			if($mn->url != '*'){
				if($mn->section_id != Menu::ADMIN){
					echo '<div class="custom-control custom-checkbox float-left mr-2">';
					echo Html::checkBox ( 'User[item][]' , true , [ 'id' => $mn->id , 'type' => 'checkbox', 'checked' => $ck, 'class' => 'custom-control-input', 'value' => $mn->id] );
					echo '<label for="'.$mn->id.'" class="custom-control-label">'.$mn->name.'</label>';
					echo '</div>';
				}
			}
		}
	}
	?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
	
    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

	<?	//= $form->field($model, 'dept')->textInput()
		echo $form->field($model, 'dept_id')->widget(Select2::classname(), [
			'data' =>  ArrayHelper::map(Dept::find()->all(), 'id', 'name'),
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

	<?//= $form->field($model, 'role')->textInput()
		echo $form->field($model, 'role')->widget(Select2::classname(), [
			'data' => ArrayHelper::map(AuthItem::find()->all(), 'name', 'name'),
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	?>

    <?//= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'status')->widget(Select2::classname(), [
			'data' => Status::statusList('User'),
			'options' => ['placeholder' => 'Select...'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);	?>

    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>

    <?//= $form->field($model, 'verification_token')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
