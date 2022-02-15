<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Registrator */

$this->title = 'Создание регистратора';
$this->params['breadcrumbs'][] = ['label' => 'Регистраторы доменных имен', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 
		
	<!-- card -->
	<div class="card registrator-create"> 
			
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