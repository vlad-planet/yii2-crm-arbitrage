<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;

/* @var $this yii\web\View */
/* @var $model backend\models\Cloudflare */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cloudflare-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_id')->textInput() ?>

    <?= $form->field($model, 'api_key')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'status')->dropDownList(Status::statusList()) ?>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
