<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tasklog */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$js = <<<JS
$('[type="radio"]').on('click', function(e) {
	id = $(this).val().toLowerCase();
	$('.type').addClass("d-none");
	$('.field-task-'+id).removeClass("d-none");
});
JS;
?>

<div class="tasklog-form">
    <?php $form = ActiveForm::begin(); ?>
	
    <?= $form->field($model, 'domains')->textarea(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'a', ['options' => ['class' => 'form-group field-task-a has-success type']] )->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'ns', ['options' => ['class' => 'form-group field-task-ns has-success type d-none']] )->textarea(['maxlength' => true]) ?>

	<?= $form->field($model, 'direction')->radioList(['A' => 'A', 'NS' => 'NS'])->label(false); ?>
		
    <?//= $form->field($model, 'created_at')->textInput() ?>
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
	
    <?php ActiveForm::end(); ?>
</div>

<? $this->registerJs($js); ?>

