<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;
use kartik\select2\Select2;
use common\models\Status;
use backend\models\Domain;
use backend\models\Server;
use backend\models\Registrator;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\SearchDomain */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Панель управления доменами';
$this->params['breadcrumbs'][] =  $this->title;
?>

<?php
$srch = <<<JS
$.fn.highlight = function (b, k) {
	function l() {
		$("." + c.className).each(function (c, e) {
				var a = e.previousSibling,
				d = e.nextSibling,
				b = $(e),
				f = "";
			a && 3 == a.nodeType && (f += a.data, a.parentNode.removeChild(a));
			e.firstChild && (f += e.firstChild.data);
			b.replaceWith(f)
			d && 3 == d.nodeType && (f += d.data, d.parentNode.removeChild(d));
		})
	}
	function h(b) {
		b = b.childNodes;
		for (var e = b.length, a; a = b[--e];)
			if (3 == a.nodeType) {
				if (!/^\s+$/.test(a.data)) {
					var d = a.data,
					d = d.replace(m, '<span class="' + c.className + '">$1</span>');
				$(a).replaceWith(d)
			}
		} else 1 == a.nodeType && a.childNodes && (!/(script|style)/i.test(a.tagName) && a.className != c.className) && h(a)
	}
	var c = {
		split: "\\s+",
		className: "highlight",
		caseSensitive: 1,
		strictly: 1,
		remove: !0
	}, c = $.extend(c, k);
	c.remove && l();
	b = $.trim(b);
	var g = c.strictly ? "" : "\\S*",
		m = RegExp("(" + g + b.replace(RegExp(c.split, "g"), g + "|" + g) + g + ")", (c.caseSensitive ? "" : "i") + "g");
	return this.each(function () {
		b && h(this)
	})
};
JS;
?><? $this->registerJs($srch); ?>

<?php
$btn = <<<JS
$(".ftp").on('click', function (e) {
		console.log();
		$.ajax({
         	url: "/domain/folder",
         	type : "POST",
         	data : {id : e.currentTarget.id},
         	success : function(res){
			res = JSON.parse(res);
			if(res['type'] == 1){
				dir = '/var/www/admin/data/www/';
				type = 'FTP-SSH';
				port = 22;
			}
			if(res['type'] == 2){
				dir = '/www/';
				type = 'FTP';
				port = 21;
			}
			dir = '"directory":"'+dir+e.currentTarget.title+'"';
			arr = '{"protocol":"'+type+'","ftpserverport":'+port+',"consent_necessary":1,"consent_preferences":1,"consent_statistics":1,"consent_personalized_ads":0,"consent_nonpersonalized_ads":1,"state":"browse","state2":"main",'+dir+'}';
			arr = JSON.parse(arr);
			arr = $.extend(res, arr); 
			//console.log(arr);
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
         	url: "/domain/cloak",
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
$(".loading").on('click', function (e) {
	if(!$('[name="selection[]"]').is(':checked')){
        alert('не выбран элемент');
		return false;
    }
});
$(".delete").on('click', function (e) {
	if(!$('[name="selection[]"]').is(':checked')){
        alert('не выбран элемент');
		return false;
    }
	return confirm("Действительно хотите УДАЛИТЬ выбранные элементы?");
});
JS;
?>

<?php
$chckd = <<<JS
$('.select-all').click(function (event) {
   var selected = this.checked;
   $(':checkbox').each(function () { this.checked = selected;
		if(this.checked == true){
			$("tr[data-key='"+this.id+"']").addClass('highlight');
		}else{
			$("tr[data-key='"+this.id+"']").removeClass('highlight');
		}
   });
});
$('.custom-control-label').on('click', function (e) {
	id = $(this).attr('for');
	if($('#'+id).is(':checked')){
		$("tr[data-key='"+id+"']").removeClass('highlight');
	}else{
		$("tr[data-key='"+id+"']").addClass('highlight');
	}
});
$(".grid-view table tbody tr").dblclick(function() {
	id = $(this).attr('data-key');
	if($('#'+id).is(':checked')){
		$("tr[data-key='"+id+"']").removeClass('highlight');
		$('#'+id).prop('checked',false);
	}else{
		$("tr[data-key='"+id+"']").addClass('highlight');
		$('#'+id).prop('checked',true);
	}
});
JS;
?>

<?php
$fltr = <<<JS
filter = $('#filter').val();
fl = $("input:text[name='"+filter+"']");
flv = fl.val();
fl.val('').focus().val(flv);
if(filter == "DomainSearch[name]"){
	var settings = {};
	var pattern = fl.val();
	pattern && $("td").highlight(pattern, settings)
}
$('.form-control').keyup(function() {	
	$('#filter').val($(this).attr('name'));
	ts = $(this);
	setTimeout(function(){
	  ts.blur();
	}, 1000);
});
$('.badge').on('click', function () {
	$(this).css('display', 'none');
	$(this).parent().next().css('display', 'block');
	return false;
});
$('.sl_st').change('change', function(e) {
	$.ajax({
		url: "/domain/update?id="+e.currentTarget.id,
		type : "POST",
		data : {status : $(this).val()},
		success : function(res){
			//alert(res);
		},
		error : function(){
			alert("Ошибка при отправке данных!");
		}
	});
	$(".sidebar-mini").load("#"); 
});
JS;
?>

<?= Html::input('hidden', null, null, ['id' => 'filter']) ?>
<? Pjax::begin(); ?>
<div class="row">

<!-- col-12 -->
<div class="col-12">

<!-- card -->
<div class="card card-default domain-index">

	<? Modal::begin(); ?>
	<?= Html::beginForm(['index'],'post') ?>
	<?= Html::textarea('search',null,['rows'=>6, 'cols'=>64]) ?>
	<?= Html::submitButton('Искать', ['class' => 'btn btn-success btn-sm']) ?>
	<?= Html::endForm() ?>
	<? Modal::end(); ?>

	<?=Html::beginForm(['upload'],'post');?>

	<!-- card-header -->
	<div class="card-header">

		<h3 class="card-title">
		</h3>
		
		<?= Html::a('Добавить домен', ['create'], ['class' => 'btn btn-success btn-sm ']) ?>
		<?= Html::submitButton('Загрузка файлов', ['class' => 'btn btn-info btn-sm loading']);?> 
		<?= Html::submitButton('Удалить', ['class' => 'btn btn-danger btn-sm delete', 'formaction' => '/domain/delete' ]);?>
		<?= Html::a('Массовый поиск', [''], ['class' => 'btn btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target'=>'#w0']);?>

		<!-- right block -->
		<div class="card-tools">
				<?	if(isset($lack) && $lack!=null){
			echo '<b>Ненайденные поисковые запросы: </b>';
			foreach($lack as $dmn)
			echo $dmn." ";
		} ?>
		</div>		
		<!-- /.right block -->

	</div>
	<!-- /.card-header -->

	<?php // echo $this->render('create', ['model' => new Domain()]); ?>

	<!-- card-body p-0 -->
	<div class="card-body p-0">

	<!-- START TABLE -->
	<?= GridView::widget([
		'layout' => "{items}\n{summary}", //  {pager}\n
		//'id'  => 'pjax-container-table',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'tableOptions' => [
				'class' => 'table table-striped table-hover table-responsive'
		],
		'options' => [
			'class' => 'grid-view table-responsive',
		],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
			[ 
				'class' => 'yii\grid\CheckboxColumn' ,
				'content' => function($model) {
					return  '<div class="custom-control custom-checkbox">' . Html::checkBox ( 'selection[]' , false , [ 'id' => $model->id , 'type' => 'checkbox' , 'class' => 'custom-control-input', 'value' => $model->id] ) . '<label for="'.$model->id.'" class="custom-control-label"></label></div>';
				} ,
				'header' => '<div class="custom-control custom-checkbox">' . Html::checkBox ( 'selection_all' , false , [ 'id' => 'select-all' , 'type' => 'checkbox' , 'class' => 'custom-control-input select-all' ] ) . '<label class="custom-control-label" for="select-all"></label></div>' ,
			],
            //'id',
			[
				'headerOptions' => ['title'=>'Индификатор', 'class' => 'w-10'],
				'attribute' => 'id',
				'value' => function($model){ return $model->id;},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'name',
			[
				'headerOptions' => ['title'=>'Домен'],
				'attribute' => 'name',
				'value' => function($model){return Html::a($model->name, 'https://'.$model->name,['target' => '_blank']);},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //'server_id',
			[   
				'headerOptions' =>['title'=>'IP адрес сервера, на котором размещен данный сайт'],
				'attribute'=>'server_id',
				'value'=>function($model){if($srv = Server::getServer($model->server_id)){  if($srv->type == 1){ $val = $srv->ip;}else{ $val = $srv->url_panel; }     return  Html::a(Html::encode($val), Url::to(['/server/view', 'id' => $model->server_id]));}},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
            ],
			//'error',
			//'files',
			[
				'headerOptions' =>['title'=>'Количество HTML файлов сайта'],
				'attribute'=>'files',
				'value'=>function($model){return  Html::a($model->files, '#', ['class' => 'ftp', 'id' => $model->server_id, 'title' => $model->name]);},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],  
			//'size', 
			[
				'headerOptions' =>['title'=>'Общий размер папки сайта в Мб'],
				'attribute'=>'size',
				'headerOptions' =>['title'=>'Количество HTML файлов сайта'],
				'value' => function($model) {return $model->size;},
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],			
			//'reg_id',
			[
				'headerOptions' =>['title'=>'Провайдер, у которого зарегистрирован домен'],
				'attribute'=>'reg_id',
				//'filter' => ArrayHelper::map(Registrator::find()->all(), 'id', 'prefix'),
				'filter' => Select2::widget([
									'name' => 'DomainSearch[reg_id]',
									'data' =>  array(' ' => '...')+ArrayHelper::map(Registrator::find()->all(), 'id', 'prefix'),
									'options' => ['placeholder' => 'Select...']
								]),
				'value'=>function($model) {if(!empty($model->cf_id)){$cf=Html::a(Html::encode(' +cf'), Url::to(['/cloudflare/view', 'id' => $model->cf_id]));}else{$cf='';}; return  (Html::a(Html::encode(Registrator::getRegistrator($model->reg_id)->prefix), Url::to(['/registrator/view', 'id' => $model->reg_id]))).'<sub>'.$cf.'</sub>';} ,
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
            ],
			//'status',
			[
				'headerOptions' =>['title'=>'Статус'],
				'attribute' => 'status',
				//'filter' => Status::statusList(),
				'filter' => Select2::widget([
									'name' => 'DomainSearch[status]',
									'data' => Status::statusList('Domain'),
									'options' => ['placeholder' => 'Select...']
								]),
				'value' => function($model){return Html::a(Status::statusLabel($model->status,'Domain'), Url::to(['#']), ['title' => $model->error]).Html::dropDownList('status', $model->status,Status::statusList('Domain'), ['class' => 'sl_st', 'id' =>  $model->id]);},
				'format' => 'raw',
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'end_date',
			[
				'headerOptions' =>['title'=>'Дата окончания регистрации домена'],
				'attribute' => 'end_date',
				'value' => function ($model) {return Domain::lastDays(date('d-m-Y', $model->end_date)); },
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
			//'created_at',
			[
				'headerOptions' =>['title'=>'Дата, когда домен был впервые добавлен в систему'],
				'attribute' => 'created_at',
				'value' => function ($model) {return date('d.m.Y h:i:s', $model->created_at);},
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			], 
            //'updated_at',
			[
				'headerOptions' =>['title'=>'Дата, когда какая-либо информация в рамках домена/сайта была изменена'],
				'attribute' => 'updated_at',
				'value' => function ($model) {return date('d.m.Y h:i:s', $model->updated_at);},
				'filterInputOptions' => [
					'class' => 'form-control form-control-sm', 
				],
			],
            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group"><button type="button" class="btn btn-warning dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bars"></i></button><div class="dropdown-menu">{folder}{cloak}{view}{update}{delete}</div></div>',
				//'header'=> 'Действия',
                'buttons' => [
				    'cloak' => function ($url, $model) {
                        return Html::a('<span role="button" class="fa fa-leaf"></span>', false, ['class' => 'cloak', 'id' => $model->id, 'title' => 'Подключить Cloak']);
                    },
                    'folder' => function ($url, $model) {
                        return Html::a('<span role="button" class="fa fa-folder-open"></span>', false, ['class' => 'ftp', 'id' => $model->server_id, 'title' => $model->name]);
                    },
                ],
            ],
        ],
	]); ?>
	<!-- END TABLE -->

	</div>
	<!-- /.card-body -->

	<?= Html::endForm() ?>

	<!-- card footer -->
	<div class="card-footer clearfix">
	
		<? echo \yii\widgets\LinkPager::widget([
			'pagination'=>$dataProvider->pagination,
			'options' => ['class' => 'pagination-sm'],
		]); ?>

		<div class="count-page float-right">
		<? 
			$selectEntriesCount = array(
			'50' => '50',
			'100' => '100',
			'250' => '250',
			'500' => '500',
			'1000' => '1000',
			'1000000' => 'Все',
			);
		?>
		Записей на странице: <?php echo Html::dropDownList('', 50, $selectEntriesCount, array('onchange'=>"document.location.href='/" . Yii::$app->request->pathInfo . "?entriesCount='+this.value;")); ?>
		</div>
			
	</div>
	<!-- /.card footer -->
			  
</div>
<!-- /.card -->

</div>
<!-- /.col-12 -->

</div>
<? $this->registerJs($chckd); ?>
<? $this->registerJs($fltr); ?>
<? $this->registerJs($btn); ?>
<? Pjax::end(); ?>