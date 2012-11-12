<?php
/* @var $this ReportCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Report Categories',
);

$this->menu=array(
	array('label'=>'Create ReportCategory', 'url'=>array('create')),
	array('label'=>'Manage ReportCategory', 'url'=>array('admin')),
);
?>

<h1>Report Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
