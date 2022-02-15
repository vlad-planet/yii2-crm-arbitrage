<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\select2\Select2;
use common\models\Status;
use backend\models\Staff;
use backend\models\Dept;
use backend\models\PayTools;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\StaffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Учет сотрудников'; 
$this->params['breadcrumbs'][] = $this->title;
?>	

<div class="row">

<!-- col-12 -->
<div class="col-12">

<!-- card -->
<div class="card card-default staff-index">

	<!-- card-header -->
	<div class="card-header">
	
		<h3 class="card-title">
		</h3>
		
		<?= Html::a('Добавить сотрудника', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
		<?= Html::a('Настройки системы', ['/dept'], ['class' => 'btn btn-info btn-sm']) ?>

		<!-- right block -->
		<div class="card-tools">
		</div>		
		<!-- /.right block -->
		
	</div>
	<!-- /.card-header -->

	<!-- card-body p-0 -->
	<div class="card-body p-0">

	<!-- START TABLE -->
    <?= GridView::widget([
		'layout' => "{items}\n{summary}", //  {pager}\n
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'tableOptions' => [
				'class' => 'table table-striped table-hover'
		],
		'options' => [
			'class' => 'table-responsive',
		],
        'columns' => [
            //'id',
            //'firstname',
			[
				'attribute' => 'firstname',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'status',
			[
				'attribute' => 'status',
				//'filter' => Status::statusList('Staff'),
				'filter' => Select2::widget([				
									'name' => 'StaffSearch[status]',
									'data' => Status::statusList('Staff'),
									'options' => ['placeholder' => 'Select...']
								]),
				'value' => function($model){return Html::a(Status::statusLabel($model->status,'Staff'), Url::to(['#']), ['title' => 'Дата увольнения '.date('d-m-Y',$model->dismissal_date)]);},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'phone',
			[
				'attribute' => 'phone',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'dept_id',
			[
				'attribute' => 'dept_id',
				'value' => function ($model) {if($dpt = Dept::findOne(['id' => $model->dept_id])){ return $dpt->name; }else{ return false; }},
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			
            //'paytools_id',
			[
				'attribute' => 'paytools_id',
				'value' => function ($model) {if($pt = PayTools::findOne(['id' => $model->paytools_id])){ return $pt->name; }else{ return false; }},
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'requisites',
			[
				'attribute' => 'requisites',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'salary',			
			[
				'attribute' => 'salary',
				'value' => function ($model) { return number_format($model->salary, 0, '', ' ' ).' ₽'; },
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'kpi',
			[
				'attribute' => 'kpi',
				'value' => function ($model) { return number_format($model->kpi, 0, '', ' ' ); },
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'registration_date:date',
			[
				'attribute' => 'registration_date',
				'value' => function ($model) {return date('d.m.Y', $model->registration_date);},
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'dismissal_date',
			/*
			[
				'attribute' => 'dismissal_date',
				'value' => function ($model) {if($model->status == STATUS::STATUS_DELETED){ return date('d-m-Y',$model->dismissal_date); }else{ return null; } },
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			*/
            //'experience',
			[
				'attribute' => 'experience',
				'value' => function ($model) {return Staff::lastDays(date('d-m-Y', $model->registration_date),$model->id).' дней'; },
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            [
				'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group"><button type="button" class="btn btn-warning dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bars"></i></button><div class="dropdown-menu">{view}{update}{delete}{dismissal}</div></div>',
				'buttons' => [
					'dismissal' => function ($url, $model) {
						return Html::a('<span role="button" class="fa fa-thumbs-down"></span>', '/staff/dismissal?id='.$model->id, 
						[
							'class' => 'dismissal',
							'id' => $model->id,
							'title' => 'Уволить',
							'data' =>[
								'confirm' => 'Вы уверены, что хотите уволить сотрудника: '.$model->firstname,
								//'method' => 'post',
							],
						]);
					},
				],
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
<!-- /.col-12 -->

</div>
