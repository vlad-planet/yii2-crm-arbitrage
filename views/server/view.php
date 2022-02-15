<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\models\Status;
use backend\models\Server;
use backend\models\Hoster;

/* @var $this yii\web\View */
/* @var $model backend\models\Server */

$this->title = $model->ip;
$this->params['breadcrumbs'][] = ['label' => 'Панел управления серверами', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 

	<!-- card -->
	<div class="card server-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Детальная информация о сервере</h3>
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
            'ip',
			'url_panel',
            'login',
            'password',
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
				'format' => 'raw',
			],
			'ns',
            'disc',
            'limit',
			//'status',
			[
				'attribute' => 'status',
				'value' => function ($model) {return Status::statusLabel($model->status,'Server');},
				'format' => 'raw',
			],
			[
				'label'=>'Прикрепленные домены',
				'value'=>function($model) {return  Html::a(Html::encode($model->getAmountDomains()), Url::to(['/domain/index', 'server_id' => $model->id.'.'])).' / '.$model->socket;},
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
        ],
    ]) ?>
	
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.col-md-12 -->

</div>