<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TasklogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasklogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasklog-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tasklog', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

	<?
	foreach($balance as $k=>$val){
		echo $k.': '.$val['balance'].' '.$val['currency'];
	}
	?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
			//'domains',
			[
				'attribute' => 'domains',
				'value' => $model->domains,
				'format' => 'raw',
			],
			[
				'attribute' => 'count',
				'value' => function($model){return '/'.count(explode(',',$model->domains));},
				'format' => 'raw',
			],
            //'created_at',
			[
				'attribute' => 'created_at',
				'value' => function ($model) {return date('d.m.Y h:i:s', $model->created_at);},
			], 
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
