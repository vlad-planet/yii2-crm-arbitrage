<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = 'Обновить данные о пользователе: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 
		
	<!-- card -->
	<div class="card user-update"> 
			
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