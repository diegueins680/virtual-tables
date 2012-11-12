<?php
/* @var $this ReportTypeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Report Types',
);

$this->menu=array(
	array('label'=>'Create ReportType', 'url'=>array('create')),
	array('label'=>'Manage ReportType', 'url'=>array('admin')),
);
?>

<h1>Report Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
