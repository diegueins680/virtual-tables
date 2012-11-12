<?php
/* @var $this ReportCategoryController */
/* @var $model ReportCategory */

$this->breadcrumbs=array(
	'Report Categories'=>array('index'),
	$model->report_category_id,
);

$this->menu=array(
	array('label'=>'List ReportCategory', 'url'=>array('index')),
	array('label'=>'Create ReportCategory', 'url'=>array('create')),
	array('label'=>'Update ReportCategory', 'url'=>array('update', 'id'=>$model->report_category_id)),
	array('label'=>'Delete ReportCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->report_category_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ReportCategory', 'url'=>array('admin')),
);
?>

<h1>View ReportCategory #<?php echo $model->report_category_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'report_category_id',
		'report_category_name',
	),
)); ?>
