<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->title = 'Обновление данных сотрудника: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Учет сотрудников', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 
		
	<!-- card -->
	<div class="card staff-update"> 
			
		<!-- card-body -->
		<div class="card-body">
		
		<?
			$items[] = 	[
				'label'     =>  'Персональные данные',
				'content'   =>  $this->render('_form', ['model' => $model]),
					'headerOptions' => [
					'class' => 'nav-link'
				],
			];
			$items[] = 	[
				'label'     => 'Документы',
				'content'   =>  $this->render('_upload', ['model' => $model]),
				'headerOptions' => [
					'class' => 'nav-link'
				],
			];
			echo Tabs::widget([
				'items' => $items,
			]);
		?>

		</div>
		<!-- /.card-body -->
				
		<div class=""></div>
				
	</div>
	<!-- /.card -->
			
</div> 
<!-- /.col-md-12 -->

</div>
