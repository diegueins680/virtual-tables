<?php
/* @var $this TaController */
/* @var $model Ta */

$this->breadcrumbs=array(
	'Tas'=>array('index'),
	$model->table_id,
);

$this->menu=array(
	array('label'=>'List Ta', 'url'=>array('index')),
	array('label'=>'Create Ta', 'url'=>array('create')),
	array('label'=>'Update Ta', 'url'=>array('update', 'id'=>$model->table_id)),
	array('label'=>'Delete Ta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->table_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ta', 'url'=>array('admin')),
);
?>

<h1>View Ta #<?php echo $model->table_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'table_id',
		'table_name',
	),
)); ?>
