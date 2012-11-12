<?php
/* @var $this CallListController */
/* @var $model CallList */

$this->breadcrumbs=array(
	'Call Lists'=>array('index'),
	$model->call_list_id,
);

$this->menu=array(
	array('label'=>'List CallList', 'url'=>array('index')),
	array('label'=>'Create CallList', 'url'=>array('create')),
	array('label'=>'Update CallList', 'url'=>array('update', 'id'=>$model->call_list_id)),
	array('label'=>'Delete CallList', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->call_list_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CallList', 'url'=>array('admin')),
);
?>

<h1>View CallList #<?php echo $model->call_list_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'call_list_id',
		'callback_date_time',
		'call_date_time',
		'campaign_id',
		'employee_id',
		'phone_number',
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
	),
)); ?>
