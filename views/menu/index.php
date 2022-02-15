<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройка меню';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

<!-- col-12 -->
<div class="col-12">

<!-- card -->
<div class="card card-default menu-index">

	<!-- card-header -->
	<div class="card-header">
	
		<h3 class="card-title">
		<?= Html::a('Создать пункт меню', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
		</h3>
		
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
		'layout' => "{items}", //  \n{summary}\n{pager}
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'tableOptions' => [
				'class' => 'table table-striped table-hover'
		],
		'options' => [
				'class' => 'table-responsive'
		],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'parent_id',
			[
				'attribute' => 'parent_id',
				'value' => function($model){if($model->parent_id != null){return Menu::findOne([$model->parent_id])->name;}else{ $model->parent_id; }},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'name',
			[
				'attribute' => 'name',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'url',
			[
				'attribute' => 'url',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'icon',
			[
				'attribute' => 'icon',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'section_id',
			[
				'attribute' => 'section_id',
				'value' => function($model){return Menu::SECTION[$model->section_id];},
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
<!-- /.col-12 -->

</div>
<!-- /.card -->

</div>