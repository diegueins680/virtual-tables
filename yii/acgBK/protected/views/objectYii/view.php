<?php
/* @var $this ObjectYiiController */
/* @var $model ObjectYii */

$this->breadcrumbs=array(
	'Object Yiis'=>array('index'),
	$model->object_id,
);

$this->menu=array(
	array('label'=>'List ObjectYii', 'url'=>array('index')),
	array('label'=>'Create ObjectYii', 'url'=>array('create')),
	array('label'=>'Update ObjectYii', 'url'=>array('update', 'id'=>$model->object_id)),
	array('label'=>'Delete ObjectYii', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->object_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ObjectYii', 'url'=>array('admin')),
);
?>

<h1>View ObjectYii #<?php echo $model->object_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'object_id',
		'table_id',
	),
)); ?>
