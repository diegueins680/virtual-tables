<?php
/* @var $this HopDataController */
/* @var $model HopData */
//var_dump($_POST);
$this->breadcrumbs=array(
	'Hop Datas'=>array('index'),
	$model->idhop_data_id,
);

$this->menu=array(
	array('label'=>'List HopData', 'url'=>array('index')),
	array('label'=>'Create HopData', 'url'=>array('create')),
	array('label'=>'Update HopData', 'url'=>array('update', 'id'=>$model->idhop_data_id)),
	array('label'=>'Delete HopData', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idhop_data_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage HopData', 'url'=>array('admin')),
);
?>

<h1>View HopData #<?php echo $model->idhop_data_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idhop_data_id',
		'vendor_id',
		'agent_id',
		//'add_id',
		'util_type',
		'program_code',
		'sales_state',
		'auth_fname',
		'auth_mi',
		'auth_lname',
		'bill_fname',
		'bill_mi',
		'bill_lname',
		'company_name',
		'company_title',
		'btn',
		'serv_address',
		'serv_city',
		'serv_state',
		'serv_zip',
		'serv_county',
		'bill_address',
		'bill_city',
		'bill_state',
		'bill_zip',
		'acct_type',
		'acct_num',
		'meter_num',
		'rate_class',
		'lead_type',
		'customer_name_key',
		'email_address',
		'serv_ref_num',
		'epod_id',
		'tax_id',
	),
)); ?>
