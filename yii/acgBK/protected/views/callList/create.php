<?php
/* @var $this CallListController */
/* @var $model CallList */

$this->breadcrumbs=array(
	'Call Lists'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CallList', 'url'=>array('index')),
	array('label'=>'Manage CallList', 'url'=>array('admin')),
);
?>

<h1>Create CallList</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>