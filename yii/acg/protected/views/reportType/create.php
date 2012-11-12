<?php
/* @var $this ReportTypeController */
/* @var $model ReportType */

$this->breadcrumbs=array(
	'Report Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ReportType', 'url'=>array('index')),
	array('label'=>'Manage ReportType', 'url'=>array('admin')),
);
?>

<h1>Create ReportType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>