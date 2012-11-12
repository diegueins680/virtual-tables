<?php
/* @var $this ReportTypeController */
/* @var $model ReportType */

$this->breadcrumbs=array(
	'Report Types'=>array('index'),
	$model->report_type_id,
);

$this->menu=array(
	array('label'=>'List ReportType', 'url'=>array('index')),
	array('label'=>'Create ReportType', 'url'=>array('create')),
	array('label'=>'Update ReportType', 'url'=>array('update', 'id'=>$model->report_type_id)),
	array('label'=>'Delete ReportType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->report_type_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ReportType', 'url'=>array('admin')),
);
?>

<h1>View ReportType #<?php echo $model->report_type_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'report_type_id',
		'report_name',
		'function_name',
		'report_category_id',
	),
)); ?>
