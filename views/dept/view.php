<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Dept */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 

	<!-- card -->
	<div class="card dept-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Детальная информация об отделе</h3>
		</div>  
		<!-- /.card-header -->
		
		<!-- card-body -->
		<div class="card-body">  

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
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
            'priority',
        ],
    ]) ?>

		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.col-md-12 -->

</div>