<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Registrator */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Регистраторы доменных имен', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 

	<!-- card -->
	<div class="card registrator-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Детальная информация о регистраторе</h3>
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
            'name',
			'prefix',
            'ip',
			'login',
            'password',
            'user',
            'api_key',
			'api_url',
        ],
    ]) ?>
	
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.col-md-12 -->

</div>