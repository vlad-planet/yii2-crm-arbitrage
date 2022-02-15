<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\UploadForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = ['label' => 'Учет сотрудников', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Добавить сотрудника', 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="docs-view">

    <h1><?//= Html::encode($this->title) ?></h1>
	
<?	$form = ActiveForm::begin([
		'options'=>['enctype'=>'multipart/form-data']
	]);
	$upload = new UploadForm();
	echo $form->field($upload, 'imageFiles[]')->widget(FileInput::classname(), [
		'options'=>[
		'multiple' => true,
		 'id' => 'imageFile',
		],
			'pluginOptions' => [
				'uploadUrl' => Url::to(['upload?id='.$model->id]),
				'deleteUrl' => Url::to(['remove?id='.$model->id]),					
			]
	]);
?>
<?php ActiveForm::end(); ?>

<?= Html::a('Сохранить', ['view?id='.$model->id], ['class' => 'btn btn-success']) ?>

</div>
