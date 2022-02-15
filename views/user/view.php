<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Status;
use backend\models\Menu;
use backend\models\AuthAssignment;
use backend\models\Dept;
/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-10"> 

	<!-- card -->
	<div class="card user-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Детальная информация о пользователе</h3>
		</div>  
		<!-- /.card-header -->
		
		<!-- card-body -->
		<div class="card-body">  

		<p>
		<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
		<?= Html::a('Удалить', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger btn-sm',
				'data' => [
					'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
					'method' => 'post',
				],
			]) ?>
		</p>
		
		<? $role = AuthAssignment::findOne(['user_id' => $model->id])->item_name; ?>
		
		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'id',
				'username',
				'lastname',
				//'dept_id',
				[
					'attribute' => 'dept_id',
					'value' => function ($model) {if($dpt = Dept::findOne(['id' => $model->dept_id])){ return $dpt->name; }else{ return false; }},
					'format' => 'raw',
				],		
				[
					'attribute' => 'role',
					'value' => $role,
					'format' => 'raw',
				],
				//'auth_key',
				//'password_hash',
				//'password_reset_token',
				'email:email',
				//'status',
				[
					'attribute' => 'status',
					'value' => function ($model) {return Status::statusLabel($model->status,'User');},
					'format' => 'raw',
				],
				//'created_at',
				[
					'attribute' => 'created_at',
					'value' => function ($model) {return date('d.m.Y h:i:s', $model->created_at);},
				], 
				//'updated_at',
				[
					'attribute' => 'updated_at',
					'value' => function ($model) {return date('d.m.Y h:i:s', $model->updated_at);},
				],
				//'verification_token',
			],
		]) ?>
		
		<?
		/*
		echo Html::ol($posts, ['item' => function($item, $index) {
				 return Html::tag(
					 'li',
					 $this->render('post', ['item' => $item]),
					['class' => 'post']
				);
		}, 'class' => 'myclass']);
		*/
		?>
			
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.col-md-10 -->

<!-- col-md-2 -->
<div class="col-md-2"> 

	<!-- card -->
	<div class="card user-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Доступы к разделам</h3>
		</div>  
		<!-- /.card-header -->
		
		<!-- card-body -->
		<div class="card-body">  

	<ol>
	<?		$sgmt = AuthAssignment::findOne(['user_id' => $model->id]);	
			if($sgmt->item_id != null){
				$mn = Menu::find()->all();
				foreach($mn as $mn){
					
					if($role == 'admin'){
						echo '<li> '.$mn->name.' </li>';
					}else{
						if(in_array($mn->id, unserialize($sgmt->item_id))){
							echo '<li> '.$mn->name.' </li>';
						}
					}
				}
			}		?>
	</ol>
	
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.col-md-2 -->

</div>