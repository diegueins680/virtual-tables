<?php
/* @var $this HopDataController */
/* @var $model HopData */
$this->breadcrumbs=array(
	'Hop Datas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List HopData', 'url'=>array('index')),
	array('label'=>'Create HopData', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('hop-data-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Hop Datas</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>
<!-- search-form -->

<?php 
$this->widget
(
		'zii.widgets.grid.CGridView', 
		[
			'id'=>'hop-data-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>
			[
				'idhop_data_id',
				'bill_fname',
				'bill_lname',
				'btn',
				'company_name',
		//		'vendor_id',
				'agent_id',
		//		'add_id',
				//'util_type',
				'acct_num',
				'program_code',
				/*
				'sales_state',
				'auth_fname',
				'auth_mi',
				'auth_lname',
				'bill_mi',
				
				'company_title',
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
				*/
				[
					'class'=>'CButtonColumn',
				],
			],
		]
); ?>
