<?php
/* @var $this HopDataController */
/* @var $model HopData */

$this->breadcrumbs=array(
	'Hop Datas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List HopData', 'url'=>array('index')),
	array('label'=>'Manage HopData', 'url'=>array('admin')),
);
?>

<h1>Create HopData</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>