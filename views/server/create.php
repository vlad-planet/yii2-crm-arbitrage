<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\Server */

if($type == 1){$this->title = 'Создание сервереа';}
if($type == 2){$this->title = 'Создание хостинга';}
$this->params['breadcrumbs'][] = ['label' => 'Панел управления серверами', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
<!-- col-md-12 -->
<div class="col-md-12"> 
		
	<!-- card -->
	<div class="card server-create"> 

		<!-- card-body -->
		<div class="card-body">  

		<?
		if($data){
			foreach($data as $data){
				echo $data."<br>";
			}
			echo '<hr>';
		}
		?>
		
		<?	if($type == 1){
				echo $this->render('_form', [
					'model' => $model,
					'type' => $type,
				]);
			} ?>

		<?	if($type == 2){
				$items[] = 	[
					'label'     =>  'Добавление по маске',
					'content'   =>  $this->render('_form', ['model' => $model, 'type' =>  $type]),
						'headerOptions' => [
						'class' => 'nav-link'
					],
				];
				$items[] = 	[
					'label'     => 'Добавить строку',
					'content'   =>  $this->render('_group', ['models' => $models, 'type' =>  $type]),
					'headerOptions' => [
						'class' => 'nav-link'
					],
				];
				$items[] = 	[
					'label'     => 'Добавить хостера',
					'content'   =>  $this->render('_hoster', ['models' => $models, 'type' =>  $type]),
					'headerOptions' => [
						'class' => 'nav-link'
					],
				];

				if(isset($active)){
					$attr = ['active' => true];
					$items[$active] += $attr;
				}

				echo Tabs::widget([
					'items' => $items,
				]);
			}
		?>

		</div>
		<!-- /.card-body -->
				
		<div class=""></div>
				
	</div>
	<!-- /.card -->
			
</div> 
<!-- /.col-md-12 -->
</div>