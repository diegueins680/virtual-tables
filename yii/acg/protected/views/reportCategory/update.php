<?php
/* @var $this ReportCategoryController */
/* @var $model ReportCategory */

$this->breadcrumbs=array(
	'Report Categories'=>array('index'),
	$model->report_category_id=>array('view','id'=>$model->report_category_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ReportCategory', 'url'=>array('index')),
	array('label'=>'Create ReportCategory', 'url'=>array('create')),
	array('label'=>'View ReportCategory', 'url'=>array('view', 'id'=>$model->report_category_id)),
	array('label'=>'Manage ReportCategory', 'url'=>array('admin')),
);
?>

<h1>Update ReportCategory <?php echo $model->report_category_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>