<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\models\Status;
use backend\models\Domain;

/* @var $this yii\web\View */
/* @var $model backend\models\Cloudflare */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Управления аккаунтами Cloudflares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 

	<!-- card -->
	<div class="card cloudflare-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Детальная информация о аккаунте CloudFlare</h3>
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
			[
				'label'=>'Домен',
				'value' => function($model){if($dmn = Domain::findOne(['cf_id' => $model->id])){return Html::a(Html::encode($dmn->name), Url::to(['/domain/view', 'id' => $dmn->id]));}},
				'format' => 'raw',
            ],
            'email:email',
            'account_id',
            'api_key',
            //'status',
			[
				'attribute' => 'status',
				'value' => function($model){return Html::a(Status::statusLabel($model->status,'CloudFlare'), Url::to(['#']));},
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