<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegistratorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Регистраторы доменных имен';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

<!-- col-12 -->
<div class="col-12">

<!-- card -->
<div class="card card-default registrator-index">

	<!-- card-header -->
	<div class="card-header">
	
		<h3 class="card-title">
		</h3>
		
		<?= Html::a('Добавить регистратора', ['create'], ['class' => 'btn btn-success btn-sm']) ?>

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
        //'filterModel' => $searchModel,
		'tableOptions' => [
				'class' => 'table table-striped table-hover'
		],
		'options' => [
			'class' => 'table-responsive',
		],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            'name',
			'prefix',
            'ip',
			'login',
            'password',
            'user',
            'api_key',
			'api_url',
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