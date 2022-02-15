<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Server */

$this->title = 'Обновление сервера: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Панел управления серверами', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 
		
	<!-- card -->
	<div class="card server-update"> 
			
		<!-- card-body -->
		<div class="card-body">

		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
		
		</div>
		<!-- /.card-body -->
				
		<div class=""></div>
				
	</div>
	<!-- /.card -->
			
</div> 
<!-- /.col-md-12 -->

</div>