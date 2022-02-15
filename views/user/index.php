<?php

use yii\helpers\Html;
use yii\grid\GridView;

use backend\models\AuthAssignment;
use backend\models\Dept;

use common\models\Status;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управление пользователями';
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
			Список пользоваьелей
		</h3>

		<!-- right block -->
		<div class="card-tools">
		<?//= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
		</div>		
		<!-- /.right block -->
		
	</div>
	<!-- /.card-header -->

	<!-- card-body p-0 -->
	<div class="card-body p-0">
	<!-- START TABLE -->
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?= GridView::widget([
		'layout' => "{items}\n{summary}", //  {pager}\n
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
			'id',
			'username',
			'lastname',
			//'dept_id',
			[
				'attribute' => 'dept_id',
				'value' => function ($model) {if($dpt = Dept::findOne(['id' => $model->dept_id])){ return $dpt->name; }else{ return false; }},
			],
			//'auth_key',
			//'password_hash',
			//'password_reset_token',
			'email:email',
			[
				'attribute' => 'role',
				'value' => function ($model) {return AuthAssignment::findOne(['user_id' => $model->id])->item_name;},
				'format' => 'raw',
			],
			//'status',
			[
				'attribute' => 'status',
				'filter' => Status::statusList('User'),
				'value' => function ($model) {return Status::statusLabel($model->status,'User');},
				'format' => 'raw',
			],
			//'created_at',
			[
				'attribute' => 'created_at',
				'value' => function ($model) {return date('d.m.Y h:i:s', $model->created_at);},
			], 
			//'updated_at',
			[
				'attribute' => 'updated_at',
				'value' => function ($model) {return date('d.m.Y h:i:s', $model->updated_at);},
			],
			//'verification_token',
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