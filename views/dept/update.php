<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Dept */

$this->title = 'Обновить отдел: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Учет сотрудников', 'url' => ['/staff']];
$this->params['breadcrumbs'][] = ['label' => 'Настройки системы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 
		
	<!-- card -->
	<div class="card dept-update"> 
			
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
