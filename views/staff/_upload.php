<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use common\models\UploadForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Staff */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="staff-upload">

	<? Pjax::begin(); ?>
	
	<? 	if(Yii::$app->controller->action->id != 'create'){  ?>

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
				    'browseClass' => 'btn btn-success',
					'uploadClass' => 'btn btn-info',
					'removeClass' => 'btn btn-danger',
					'removeIcon' => '<i class="fas fa-trash"></i> ',
					'uploadUrl' => Url::to(['upload?id='.$model->id]),
					'deleteUrl' => Url::to(['remove?id=2']),					
				]
		])->label(false);
	?>
	<?php ActiveForm::end(); ?>

	<div class="form-group">
	<?php
		//if(Yii::$app->controller->action->id == 'update'){ 
			$dir = "/backend/web/upload/".$model->id."/";
			$handle = $_SERVER['DOCUMENT_ROOT'].$dir;
			if(is_dir($handle)){
				$pn = opendir($handle);
				while($file = readdir($pn)){
					if($file !== '.' && $file !== '..'){
						echo '<span class="float-left"><img  width="100" height="100" src="'.$dir.$file.'" border="0" /><a href="/staff/update?id='.$model->id.'&file='.$file.'" class="fa fa-times align-top"></a></span>';
					}
				}
			}
		//}
	?>
	</div>

	<? } ?>
	
	<? Pjax::end(); ?>
	
</div>
