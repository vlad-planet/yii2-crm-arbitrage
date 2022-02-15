<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use backend\models\Hoster;

/* @var $this yii\web\View */
/* @var $model backend\models\Server */
/* @var $form yii\widgets\ActiveForm */
?>

	<!-- card -->
	<div class="card card-default hoster-form">

		<!-- card-header -->
		<div class="card-header">
		
			<h3 class="card-title">Список хостеров: </h3>

			<!-- right block -->
			<div class="card-tools">
			
			<?php $model = new Hoster();  ?>
			<?php $form = ActiveForm::begin(['action' => ['/hoster/create'],'options' => ['method' => 'post']]); ?>
			Добавить элемет в список: <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'form-control form-control-sm'])->label(false); ?>
			<?php ActiveForm::end(); ?>
			</div>		
			<!-- /.right block -->
			
		</div>
		<!-- /.card-header -->

		<!-- card-body p-0 -->
		<div class="card-body p-0">

		<!-- START TABLE -->
		<?
		$dataProvider = new ActiveDataProvider([
			'query' => Hoster::find(),
			'pagination' => [
				'pageSize' => 20,
			],
		]);
		echo GridView::widget([
			'layout' => "{items}", // \n{summary} \n{pager}
			'dataProvider' => $dataProvider,
			'tableOptions' => [
					'class' => 'table table-striped table-hover'
			],
			'options' => [
				'class' => 'table-responsive',
			],
			'columns' => [
				'id',
				//'name',
				[
					'attribute' => 'name',
					'filterInputOptions' => [
						'class' => 'form-control form-control-sm', 
					],
				],
				[
					'class' => 'yii\grid\ActionColumn',
					'template' => '<div class="btn-group"><button type="button" class="btn btn-warning dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bars"></i></button><div class="dropdown-menu">{delete}</div></div>',
					'controller' => 'hoster',
					//'header'=> 'Действия',
				],
			]
		]);
		?>
		<!-- END TABLE -->

		</div>
		<!-- /.card-body -->
		
		<!-- card footer -->
		<div class="card-footer clearfix">
		
			<? echo \yii\widgets\LinkPager::widget([
				'pagination'=>$dataProvider->pagination,
				'options' => ['class' => 'pagination-sm'],
			]); ?>
			
		</div>
		<!-- /.card footer -->
		
	</div>
	<!-- /.card -->
	
