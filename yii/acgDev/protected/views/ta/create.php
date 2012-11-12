<?php
/* @var $this TaController */
/* @var $model Ta */

$this->breadcrumbs=array(
	'Tas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ta', 'url'=>array('index')),
	array('label'=>'Manage Ta', 'url'=>array('admin')),
);
?>

<h1>Create Ta</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>