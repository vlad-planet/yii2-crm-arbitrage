<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\select2\Select2;

use common\models\Status;
use backend\models\Server;
use backend\models\Hoster;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ServerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Панель управления серверами';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$fltr = <<<JS
$('.select-all').click(function (event) {
   var selected = this.checked;
   $(':checkbox').each(function () { this.checked = selected;
		if(this.checked == true){
			$("tr[data-key='"+this.id+"']").addClass('highlight');
		}else{
			$("tr[data-key='"+this.id+"']").removeClass('highlight');
		}
   });
});
$('.custom-control-label').on('click', function (e) {
	id = $(this).attr('for');
	if($('#'+id).is(':checked')){
		$("tr[data-key='"+id+"']").removeClass('highlight');
	}else{
		$("tr[data-key='"+id+"']").addClass('highlight');
	}
});
$("table tbody tr").dblclick(function() {
	id = $(this).attr('data-key');
	if($('#'+id).is(':checked')){
		$("tr[data-key='"+id+"']").removeClass('highlight');
		$('#'+id).prop('checked',false);
	}else{
		$("tr[data-key='"+id+"']").addClass('highlight');
		$('#'+id).prop('checked',true);
	}
});

$('.status').parent().addClass('row-warning');
JS;
?>

<?php
$btn = <<<JS
$(".delete").on('click', function (e) {
	if(!$('[name="selection[]"]').is(':checked')){
        alert('не выбран элемент');
		return false;
    }
	ok = confirm('Действительно хотите УДАЛИТЬ выбранные элемнтв?');
	return ok;
});
JS;
?>
<div class="row">

<!-- col-12 -->
<div class="col-12">

<!-- card -->
<div class="card card-default server-index">


	<?=Html::beginForm(['upload'],'post');?>
	
	<!-- card-header -->
	<div class="card-header">
	 
		<h3 class="card-title">
		</h3>
		
		<?= Html::a('Добавить сервер', ['create?type=1'], ['class' => 'btn btn-success btn-sm']) ?>
		<?= Html::a('Добавить хостинг', ['create?type=2'], ['class' => 'btn btn-success btn-sm']) ?>
		<?= Html::submitButton('Удалить', ['class' => 'btn btn-sm btn-danger delete', 'formaction' => '/server/delete']);?>

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
			'class' => 'grid-view table-responsive',
		],
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],
			[ 
				'class' => 'yii\grid\CheckboxColumn' ,
				'content' => function($model) {
					return  '<div class="custom-control custom-checkbox">' . Html::checkBox ( 'selection[]' , false , [ 'id' => $model->id , 'type' => 'checkbox' , 'class' => 'custom-control-input', 'value' => $model->id] ) . '<label for="'.$model->id.'" class="custom-control-label"></label></div>';
				} ,
				'header' => '<div class="custom-control custom-checkbox">' . Html::checkBox ( 'selection_all' , false , [ 'id' => 'select-all' , 'type' => 'checkbox' , 'class' => 'custom-control-input select-all' ] ) . '<label class="custom-control-label" for="select-all"></label></div>' ,
			],
			//'id',
			//'ip',
			[
				'attribute' => 'ip',
				'value' => function ($model) { if($model->type == 2){ return $model->url_panel; }else{ return $model->ip ;} },
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'login',
			[
				'attribute' => 'login',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'password',
			[
				'attribute' => 'password',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'hoster_id',
			[
				'attribute' => 'hoster_id',
				'value' => function ($model) {return Hoster::findOne(['id' => $model->hoster_id])->name;},
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'type',
			[
				'attribute' => 'type',
				'value' => function ($model) {return Server::TYPES[$model->type];},
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'ns',
			[
				'attribute' => 'ns',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'disc',
			[
				'attribute' => 'disc',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'limit',
			[
				'attribute' => 'limit',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'status',
			//'error',
			[
				'attribute' => 'status',
				'contentOptions' => function($model){ if($model->status != Status::STATUS_ACTIVE) {return [ 'class' =>'status'];}else{return [ 'class' =>''];}} ,
				//'filter' => Status::statusList('Server'),
				'filter' => Select2::widget([
									'name' => 'ServerSearch[status]',
									'data' => Status::statusList('Server'),
									'options' => ['placeholder' => 'Select...']
								]),
				'value' => function($model){return Html::a(Status::statusLabel($model->status,'Server'), Url::to(['#']), ['title' => $model->error]);},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			[
				'label'=>'Домены',
				'content'=>function($model) {return  Html::a(Html::encode($model->getAmountDomains()), Url::to(['/domain/index', 'server_id' => $model->id.'.'])).' / '.$model->socket;},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'created_at',
			[
				'attribute' => 'created_at',
				'value' => function ($model) {return date('d.m.Y h:i:s', $model->created_at);},
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			], 
			//'updated_at',
			[
				'attribute' => 'updated_at',
				'value' => function ($model) {return date('d.m.Y h:i:s', $model->updated_at);},
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
	
	<?= Html::endForm() ?>
	
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
<? $this->registerJs($fltr); ?>
<? $this->registerJs($btn); ?>