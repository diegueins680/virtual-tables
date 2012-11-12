<?php
/* @var $this CallListController */
/* @var $model CallList */

$this->breadcrumbs=array(
	'Call Lists'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List CallList', 'url'=>array('index')),
	array('label'=>'Create CallList', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('call-list-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Call Lists</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'call-list-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'call_list_id',
		'callback_date_time',
		'call_date_time',
		'campaign_id',
		'employee_id',
		'phone_number',
		/*
		'firstname',
		'lastname',
		'e_mail_id',
		'street',
		'st_or_province',
		'city',
		'postal_code',
		'time_zone_id',
		'leadcode',
		'list_id',
		'priority',
		'last_termination',
		'comment',
		'date_inserted',
		'upload_id',
		'callerid_num',
		'callerid_name',
		'hold_time',
		'customerId',
		'account_code',
		'locale_id',
		'test_id',
		'deleted',
		'prev_termination',
		'temp_call_list_id',
		'callable',
		'transfer_callerid_name',
		'transfer_callerid_num',
		'call_count',
		'dial_count',
		'max_dial_count',
		'max_call_count',
		'rule_id',
		'phone_type_id',
		'phone_loaded_index',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
