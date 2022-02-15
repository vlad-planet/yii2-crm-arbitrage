<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use backend\models\PayTools;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeptSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройки системы';
$this->params['breadcrumbs'][] = ['label' => 'Учет сотрудников', 'url' => ['/staff']];

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

<!-- col-6 -->
<div class="col-md-6">

	<!-- card -->
	<div class="card card-default dept-index">

		<!-- card-header -->
		<div class="card-header">
		
			<h3 class="card-title">Управление отделами</h3>

			<!-- right block -->
			<div class="card-tools">
				<?= Html::a('Добавить отдел', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
			</div>		
			<!-- /.right block -->
			
		</div>
		<!-- /.card-header -->

		<!-- card-body p-0 -->
		<div class="card-body p-0">

		<!-- START TABLE -->
		<?= GridView::widget([
			'layout' => "{items}", // \n{summary} \n{pager}
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'tableOptions' => [
					'class' => 'table table-striped table-hover'
			],
			'options' => [
				'class' => 'table-responsive',
			],
			'columns' => [
				//['class' => 'yii\grid\SerialColumn'],
				//'id',
				//'name',
				[
					'attribute' => 'name',
					'filterInputOptions' => [
						'class' => 'form-control form-control-sm', 
					],
				],
				//'priority',
				[
					'attribute' => 'priority',
					'filterInputOptions' => [
						'class' => 'form-control form-control-sm', 
					],
				],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group"><button type="button" class="btn btn-warning dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bars"></i></button><div class="dropdown-menu">{view}{update}{delete}</div></div>',
				//'header'=> 'Действия',
			],
			],
		]); ?>
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
	
</div>
<!-- /.col-6 -->

<!-- col-6 -->
<div class="col-md-6">

	<!-- card -->
	<div class="card card-default dept-index">

		<!-- card-header -->
		<div class="card-header">
		
			<h3 class="card-title">Платежные инструменты: </h3>

			<!-- right block -->
			<div class="card-tools">
			
			<?php $model = new PayTools();  ?>
			<?php $form = ActiveForm::begin(['action' => ['pay-tools/create'],'options' => ['method' => 'post']]); ?>
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
			'query' => PayTools::find(),
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
					'controller' => 'pay-tools',
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
	
</div>
<!-- /.col-6 -->

</div>
<!-- /.col-12 -->

</div>












