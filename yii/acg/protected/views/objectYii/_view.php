<?php
/* @var $this ObjectYiiController */
/* @var $model ObjectYii */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('object_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->object_id), array('view', 'id'=>$data->object_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('table_id')); ?>:</b>
	<?php echo CHtml::encode($data->table_id); ?>
	<br />


</div>