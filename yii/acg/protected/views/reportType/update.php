<?php
/* @var $this ReportTypeController */
/* @var $model ReportType */

$this->breadcrumbs=array(
	'Report Types'=>array('index'),
	$model->report_type_id=>array('view','id'=>$model->report_type_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ReportType', 'url'=>array('index')),
	array('label'=>'Create ReportType', 'url'=>array('create')),
	array('label'=>'View ReportType', 'url'=>array('view', 'id'=>$model->report_type_id)),
	array('label'=>'Manage ReportType', 'url'=>array('admin')),
);
?>

<h1>Update ReportType <?php echo $model->report_type_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>