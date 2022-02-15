<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Настройка меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 

	<!-- card -->
	<div class="card menu-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Детальная информация о настройке пункта меню </h3>
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
            'parent_id',
            'name',
            'url:url',
            'icon',
            'section_id',
        ],
    ]) ?>

		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.col-md-12 -->

</div>