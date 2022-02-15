<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RegistratorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registrator-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

	<?= $form->field($model, 'prefix') ?>

    <?= $form->field($model, 'ip') ?>
	
    <?= $form->field($model, 'login') ?>
	
    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'user') ?>

    <?// = $form->field($model, 'api_key') ?>
	
	<?// = $form->field($model, 'api_url') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
