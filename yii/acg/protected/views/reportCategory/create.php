<?php
/* @var $this ReportCategoryController */
/* @var $model ReportCategory */

$this->breadcrumbs=array(
	'Report Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ReportCategory', 'url'=>array('index')),
	array('label'=>'Manage ReportCategory', 'url'=>array('admin')),
);
?>

<h1>Create ReportCategory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>