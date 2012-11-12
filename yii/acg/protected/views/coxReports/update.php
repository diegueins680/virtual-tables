<?php
/* @var $this HopDataController */
/* @var $model HopData */

$this->breadcrumbs=array(
	'Hop Datas'=>array('index'),
	$model->idhop_data_id=>array('view','id'=>$model->idhop_data_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List HopData', 'url'=>array('index')),
	array('label'=>'Create HopData', 'url'=>array('create')),
	array('label'=>'View HopData', 'url'=>array('view', 'id'=>$model->idhop_data_id)),
	array('label'=>'Manage HopData', 'url'=>array('admin')),
);
?>

<h1>Update HopData <?php echo $model->idhop_data_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>