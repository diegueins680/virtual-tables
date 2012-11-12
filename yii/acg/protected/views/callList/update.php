<?php
/* @var $this CallListController */
/* @var $model CallList */

$this->breadcrumbs=array(
	'Call Lists'=>array('index'),
	$model->call_list_id=>array('view','id'=>$model->call_list_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CallList', 'url'=>array('index')),
	array('label'=>'Create CallList', 'url'=>array('create')),
	array('label'=>'View CallList', 'url'=>array('view', 'id'=>$model->call_list_id)),
	array('label'=>'Manage CallList', 'url'=>array('admin')),
);
?>

<h1>Update CallList <?php echo $model->call_list_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>