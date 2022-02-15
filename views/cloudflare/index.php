<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\select2\Select2;

use common\models\Status;
use backend\models\Domain;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CloudflareSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управления аккаунтами Cloudflares';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

<!-- col-12 -->
<div class="col-12">

<!-- card -->
<div class="card card-default cloudflare-index">

	<!-- card-header -->
	<div class="card-header">
	
		<h3 class="card-title">
			 <?= Html::a('Добавить аккаунт Cloudflare', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
		</h3>

		<!-- right block -->
		<div class="card-tools">
			<? echo 'Количество аккаунтов: '. $count; ?>
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
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
			[
				'label'=>'Домен',
				'value' => function($model){if($dmn = Domain::findOne(['cf_id' => $model->id])){return Html::a(Html::encode($dmn->name), Url::to(['/domain/view', 'id' => $dmn->id]));}},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
            ],
            //'email:email',
			[
				'attribute' => 'email',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'account_id',
			[
				'attribute' => 'account_id',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'api_key',
			[
				'attribute' => 'api_key',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'status',
			[
				'attribute' => 'status',
				//'filter' => Status::statusList('CloudFlare'),
				'filter' => Select2::widget([
									'name' => 'CloudflareSearch[status]',
									'data' => Status::statusList('CloudFlare'),
									'options' => ['placeholder' => 'Select...']
								]),
				'value' => function($model){return Html::a(Status::statusLabel($model->status,'CloudFlare'), Url::to(['#']));},
				'format' => 'raw',
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
<!-- /.col-12 -->

</div>