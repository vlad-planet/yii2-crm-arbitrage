<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Status;
use backend\models\Dept;
use backend\models\PayTools;
use backend\models\Staff;
use backend\models\StaffLog;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->title = $model->firstname;
$this->params['breadcrumbs'][] = ['label' => 'Учет сотрудников', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 

	<!-- card -->
	<div class="card staff-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Детальная информация о сотруднике</h3>
		</div>  
		<!-- /.card-header -->
		
		<!-- card-body -->
		<div class="card-body">  

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'firstname',
			[
				'attribute' => 'status',
				'value' => function ($model) {return Status::statusLabel($model->status,'Staff');},
				'format' => 'raw',
			],
            'phone',
            //'dept_id',
			[
				'attribute' => 'dept_id',
				'value' => function ($model) {if($dpt = Dept::findOne(['id' => $model->dept_id])){ return $dpt->name; }else{ return false; }},
				'format' => 'raw',
			],
            //'paytools_id',
			[
				'attribute' => 'paytools_id',
				'value' => function ($model) {if($pt = PayTools::findOne(['id' => $model->paytools_id])){ return $pt->name; }else{ return false; }},
				'format' => 'raw',
			],
            'requisites',
            //'salary',
			[
				'attribute' => 'salary',
				'value' => function ($model) { return number_format($model->salary, 0, '', ' ' ).' ₽'; },
				'format' => 'raw',
			],
            //'kpi',
			[
				'attribute' => 'kpi',
				'value' => function ($model) { return number_format($model->kpi, 0, '', ' ' ); },
				'format' => 'raw',
			],
            //'registration_date:date',
			[
				'attribute' => 'registration_date',
				'value' => function ($model) {return date('d.m.Y', $model->registration_date);},
				'format' => 'raw',
			],
            //'dismissal_date:date',
			[
				'attribute' => 'dismissal_date',
				'value' => function ($model) {if($model->status == STATUS::STATUS_DELETED){ return date('d-m-Y',$model->dismissal_date); }else{ return null; } },
				'format' => 'raw',
			],
            //'experience',
			[
				'attribute' => 'experience',
				'value' => function ($model) {return Staff::lastDays(date('d-m-Y', $model->registration_date),$model->id).' дней'; },
				'format' => 'raw',
			],			
			
        ],
    ]) ?>
	
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.col-md-12 -->

</div>

<div class="row" >

<div class="col-md-12"> 

	<!-- card -->
	<div class="card staff-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Документы</h3>
		</div>  
		<!-- /.card-header -->
		
		<!-- card-body -->
		<div class="card-body">
	<?php
			$dir = "/backend/web/upload/".$model->id."/";
			$handle = $_SERVER['DOCUMENT_ROOT'].$dir;
			if(is_dir($handle)){
				$pn = opendir($handle);
				while($file = readdir($pn)){
					if($file !== '.' && $file !== '..'){
						echo '<img class="m-1" width="100" height="100" src="'.$dir.$file.'" border="0" />';
					}
				}
			}
	?>

		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.col-md-12 -->

</div>



<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 

	<!-- card -->
	<div class="card staff-view">
	
	<!-- card-header -->
	<div class="card-header">
	
		<h3 class="card-title">Лог изменений</h3>

		<!-- right block -->
		<div class="card-tools">
		</div>		
		<!-- /.right block -->
		
	</div>
	<!-- /.card-header -->

	<!-- card-body p-0 -->
	<div class="card-body p-0">

	<!-- START TABLE -->
		
		
	<?	
	use yii\data\ActiveDataProvider;

	$dataProvider = new ActiveDataProvider([
		'query' => StaffLog::find(),
		'pagination' => [
			'pageSize' => 20,
		],
	]);
		
	echo GridView::widget([
		'layout' => "{items}", //  \n{summary} \n{pager}
		'dataProvider' => $dataProvider,
		'columns' => [
			//'staff_id',
			'name',
			//'value',
			[
				'attribute' => 'value',
				'value' => function ($model) {  if(ctype_digit($model->value)){ return number_format($model->value, 0, '', ' ' ); }else{ return $model->value; } },
				'format' => 'raw',
			],
			'updated_at:date',
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
<!-- /.col-md-12 -->
</div>