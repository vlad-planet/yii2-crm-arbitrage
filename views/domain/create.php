<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Domain */
$this->title = 'Создание Домена';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления доменами', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 
		
	<!-- card -->
	<div class="card domain-create"> 
			
		<!-- card-body -->
		<div class="card-body">  
		
		<? 
		if($data){
				foreach($data as $data){
					echo $data."<br>";
				}
			}
			echo '<hr>';
			echo '<b>Синтаксис добавления:</b><br>';
			echo '1domain.com<br>';
			echo '2domain.com<br>';
			echo '<br>';
			echo 'Для доменов доступно <b>Слотов: '.Html::a($limit, '/server',['target' => '_blank']). '</b> &nbsp &nbsp &nbsp аккаунтов <b>ClousFlare: '.Html::a($count, '/cloudflare',['target' => '_blank']).'</b><br>';
		?>

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