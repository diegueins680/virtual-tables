<?php
/* @var $this ObjectYiiController */
/* @var $model ObjectYii */

$this->breadcrumbs=array(
	'Object Yiis'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ObjectYii', 'url'=>array('index')),
	array('label'=>'Manage ObjectYii', 'url'=>array('admin')),
);
?>

<h1>Create ObjectYii</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>