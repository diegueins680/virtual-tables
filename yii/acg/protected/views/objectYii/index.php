<?php
/* @var $this ObjectYiiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Object Yiis',
);

$this->menu=array(
	array('label'=>'Create ObjectYii', 'url'=>array('create')),
	array('label'=>'Manage ObjectYii', 'url'=>array('admin')),
);
?>

<h1>Object Yiis</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
