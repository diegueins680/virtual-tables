<?php
/* @var $this TaController */
/* @var $model Ta */

$this->breadcrumbs=array(
	'Tas'=>array('index'),
	$model->table_id=>array('view','id'=>$model->table_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ta', 'url'=>array('index')),
	array('label'=>'Create Ta', 'url'=>array('create')),
	array('label'=>'View Ta', 'url'=>array('view', 'id'=>$model->table_id)),
	array('label'=>'Manage Ta', 'url'=>array('admin')),
);
?>

<h1>Update Ta <?php echo $model->table_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>