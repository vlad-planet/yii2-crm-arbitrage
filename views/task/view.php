<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\models\Registrator;

use common\models\Status;

/* @var $this yii\web\View */
/* @var $model app\models\Tasklog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tasklogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tasklog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'domains',
			[
				'attribute' => 'domains',
				'value' => function($model){return $model->domains;},
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
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
			'name',
			//'reg_id',
			[
				'attribute' => 'reg_id',
				'value' => function($model){return Registrator::getRegistrator($model->reg_id)->prefix;},
				'format' => 'raw',
			],
			'cost',
			//'status',
			[
				'attribute' => 'status',
				'value' => function ($model) {return Status::statusLabel($model->status,'Task');},
				'format' => 'raw',
			],
			//'created_at',
			/*
			[
				'attribute' => 'created_at',
				'value' => function ($model) {return date('d.m.Y h:i:s', $model->created_at);},
			], 
			*/
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
