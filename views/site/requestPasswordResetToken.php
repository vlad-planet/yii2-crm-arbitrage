<?php
use yii\helpers\Html;
use yii\widgets\Pjax;

\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<? Pjax::begin(); ?>
<div class="card card-outline card-primary">
	<h1 class="card-header text-center font-weight-bold" >INFINITUM</h1>
    <div class="card-body login-card-body">

        <p class="login-box-msg">Пожалуйста, заполните свой адрес электронной почты. Вам будет отправлена ссылка для сброса пароля.</p>

			<?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'request-password-reset-form']) ?>
			
			<?= $form->field($model, 'email', [
				'options' => ['class' => 'form-group has-feedback'],
				'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
				'template' => '{beginWrapper}{input}{error}{endWrapper}',
				'wrapperOptions' => ['class' => 'input-group mb-3']
			])
				->label(false)
				->textInput(['placeholder' => $model->getAttributeLabel('Электронная почта')]) ?>

		<div class="row">
			<div class="col-7">
				<p class="mb-1">
					<a href="/site/login" class="text-center">Вход</a>
				</p>
				<p class="mb-0">
					<a href="/site/signup" class="text-center">Зарегистрироваться</a>
				</p>
			</div>
            <div class="col-5">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <?php \yii\bootstrap4\ActiveForm::end(); ?>

    </div>
    <!-- /.login-card-body -->
</div>
<? Pjax::end(); ?>