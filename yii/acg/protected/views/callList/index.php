<?php
/* @var $this CallListController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Call Lists',
);

$this->menu=array(
	array('label'=>'Create CallList', 'url'=>array('create')),
	array('label'=>'Manage CallList', 'url'=>array('admin')),
);
?>

<h1>Call Lists</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
