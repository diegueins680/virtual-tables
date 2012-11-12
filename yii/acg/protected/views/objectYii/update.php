<?php
/* @var $this ObjectYiiController */
/* @var $model ObjectYii */

$this->breadcrumbs=array(
	'Object Yiis'=>array('index'),
	$model->object_id=>array('view','id'=>$model->object_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ObjectYii', 'url'=>array('index')),
	array('label'=>'Create ObjectYii', 'url'=>array('create')),
	array('label'=>'View ObjectYii', 'url'=>array('view', 'id'=>$model->object_id)),
	array('label'=>'Manage ObjectYii', 'url'=>array('admin')),
);
?>

<h1>Update ObjectYii <?php echo $model->object_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>