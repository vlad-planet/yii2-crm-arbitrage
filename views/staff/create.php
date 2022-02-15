<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->title = 'Добавить сотрудника';
$this->params['breadcrumbs'][] = ['label' => 'Учет сотрудников', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 
		
	<!-- card -->
	<div class="card staff-create"> 
			
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
