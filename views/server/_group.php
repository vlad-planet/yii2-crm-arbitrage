<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use backend\models\Hoster;
/* @var $this yii\web\View */
/* @var $model backend\models\Server */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

/* @var $this yii\web\View */
/* @var $modelCustomer app\modules\yii2extensions\models\Customer */
/* @var $modelsAddress app\modules\yii2extensions\models\Address */

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Сервер: " + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Сервер: " + (index + 1))
    });
});
';

$this->registerJs($js);
?>

<div class="server-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
	<input type="hidden" name="only" value="1">

    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 4, // the maximum times, an element can be cloned (default 999)
        'min' => 1, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $models[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'url_panel',
            'ns',
			'hoster_id',
			'limit',
        ],
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="clearfix"></div>
        </div>
        <div class="panel-body container-items"><!-- widgetContainer -->
            <?php foreach ($models as $index => $model): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <span class="panel-title-address">Сервер: <?= ($index + 1) ?></span>
                        <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (!$model->isNewRecord) {
                                echo Html::activeHiddenInput($model, "[{$index}]id");
                            }
                        ?>
						
                        <?= $form->field($model, "[{$index}]url_panel")->textInput(['maxlength' => true])->label('url_panel|user|password') ?>

                        <div class="row">
							<div class="col-sm-6">
								<?= $form->field($model, "[{$index}]hoster_id")->widget(Select2::classname(), [
									'data' =>  ArrayHelper::map(Hoster::find()->all(), 'id', 'name'),
									'options' => ['placeholder' => 'Select...'],
									'pluginOptions' => [
										'allowClear' => true
									],
								]);	?>
							</div>
                            <div class="col-sm-6">
								<?= $form->field($model, "[{$index}]limit")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, "[{$index}]ns")->textarea() ?>
                            </div>
                        </div><!-- end:row -->
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group">
		<button type="button" class="pull-right add-item btn btn-success btn-sm float-right"><i class="fa fa-plus"></i> Добавить еще</button>
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Update', ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
    <?php DynamicFormWidget::end(); ?>
    <?php ActiveForm::end(); ?>

</div>