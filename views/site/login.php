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

        <p class="login-box-msg">Войдите в свою учетную запись</p>

        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']) ?>

        <?= $form->field($model,'email', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('Электронная почта')]) ?>

        <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('Пароль')]) ?>

        <div class="row">
            <div class="col-8">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'labelOptions' => [
                        'class' => ''
                    ],
                    'uncheck' => null
                ])->label('Запомнить меня') ?>

            </div>
            <div class="col-4">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>
		
        <?php \yii\bootstrap4\ActiveForm::end(); ?>
		<p class="float-right">
            <a href="/site/resend-verification-email" class="text-center">Не пришло <br>подтверждение</a>
		</p>
		<p class="mb-0">
            <a href="/site/request-password-reset">Я забыл свой пароль</a>
        </p>
        <p class="mb-0">

            <a href="/site/signup" class="text-center">Зарегистрироваться</a> 

        </p>
		<!-- /.social-auth-links -->
		
	</div>
    <!-- /.login-card-body -->
</div>
<? Pjax::end(); ?>