<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\models\Server;
use common\models\Status;
use yii\helpers\ArrayHelper;
use backend\models\Registrator;
use backend\models\Domain;

/* @var $this yii\web\View */
/* @var $model backend\models\Domain */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Панель управления доменами', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
$js = <<<JS
$(".flz").on('click', function (e) {
		console.log();
		$.ajax({
         	url: "/admin/domain/folder",
         	type : "POST",
         	data : {id : e.currentTarget.id},
         	success : function(res){

			dir = '"directory":"/var/www/admin/data/www/'+e.currentTarget.title+'"';

			res = JSON.parse(res);
			arr = '{"protocol":"FTP-SSH","ftpserverport":22,"consent_necessary":1,"consent_preferences":1,"consent_statistics":1,"consent_personalized_ads":0,"consent_nonpersonalized_ads":1,"state":"browse","state2":"main",'+dir+'}';
			arr = JSON.parse(arr);
			arr = $.extend(res, arr); 
			console.log(arr);

			keys = Object.keys(arr)
			form = document.createElement('form');
			document.body.appendChild(form);
			form.target = '_blank';
			form.method = 'post';
			form.action = '/ftp/index.php';

			for (var i = 0, l = keys.length; i < l; i++) {
				input = document.createElement('input');
				input.type = 'hidden';
				input.name = keys[i];
				input.value = arr[keys[i]];
				form.appendChild(input);
			}

			form.submit();
			},
			error : function(){
				alert("Ошибка при отправке данных!");
			}
        });
});
$(".cloak").on('click', function (e) {
		console.log();
		$.ajax({
         	url: "/admin/domain/cloak",
         	type : "POST",
         	data : {id : e.currentTarget.id},
         	success : function(res){
				
				alert(res);
			},
			error : function(){
				alert("Ошибка при отправке данных!");
			}
        });
});
JS;
?>

<div class="row">

<!-- col-md-12 -->
<div class="col-md-12"> 

	<!-- card -->
	<div class="card domain-view">
	
		<!-- card-header -->	
		<div class="card-header"> 
			<h3 class="card-title">Детальная информация о домене</h3>
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
		
		<?= Html::a('<span role="button" class="fa fa-folder-open"></span>', false, ['class' => 'flz', 'id' => $model->server_id, 'title' => $model->name]) ?>
		<?= Html::a('<span role="button" class="fa fa-leaf"></span>', false, ['class' => 'cloak', 'id' => $model->id, 'title' => 'Add Cloak']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => [
		'class' => 'table table-striped table-bordered detail-view'
		],
        'attributes' => [
            'id',
            //'name',
			[
				'attribute' => 'name',
				'value' => function($model){return Html::a($model->name, 'https://'.$model->name,['target' => '_blank']);},
				'format' => 'raw',
			],
            //'server_id',
			[
			  'format' => 'raw',
			  'attribute' => 'server_id',
			  'value' => function($model){if($srv = Server::getServer($model->server_id)){return  Html::a(Html::encode($srv->ip), Url::to(['/server/view', 'id' => $model->server_id]));}},
			],
            //'status',
			[
				'attribute' => 'status',
				'value' => function ($model) {return Status::statusLabel($model->status,'Domain');},
				'format' => 'raw',
			],
			'files',
			'size',
			//'reg_id',
			[
				'attribute'=>'reg_id',
				'filter' => ArrayHelper::map(Registrator::find()->all(), 'id', 'prefix'),
				'value'=>function($model) {if(!empty($model->cf_id)){$cf=Html::a(Html::encode(' +cf'), Url::to(['/cloudflare/view', 'id' => $model->cf_id]));}else{$cf='';}; return(Html::a(Html::encode(Registrator::getRegistrator($model->reg_id)->prefix), Url::to(['/registrator/view', 'id' => $model->reg_id]))).$cf;} ,
				'format' => 'raw',
            ],
			'error',
			//'end_date',
			[
				'attribute' => 'end_date',
				'value' => function ($model) {return Domain::lastDays(date('d-m-Y', $model->end_date));},
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
        ],
    ]) ?>
	
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.col-md-12 -->

</div>

<?$this->registerJs($js);?>
