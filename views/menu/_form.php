<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Menu;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>

	<? $mn = Menu::find()->all(); $prnt[] = null;
	foreach($mn as $mn){
		$prnt[$mn->id] = $mn->name; 
	} ?>
	
	<?= $form->field($model, 'parent_id')->dropDownList($prnt) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'section_id')->dropDownList(Menu::SECTION) ?>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>