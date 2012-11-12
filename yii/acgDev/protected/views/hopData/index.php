<?php
/* @var $this HopDataController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Hop Datas',
);

$this->menu=array(
	array('label'=>'Create HopData', 'url'=>array('create')),
	array('label'=>'Manage HopData', 'url'=>array('admin')),
);
?>

<h1>Hop Datas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
