<?php
/* @var $this TaController */
/* @var $model Ta */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('table_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->table_id), array('view', 'id'=>$data->table_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('table_name')); ?>:</b>
	<?php echo CHtml::encode($data->table_name); ?>
	<br />


</div>