<?php
/* @var $this ReportTypeController */
/* @var $data ReportType */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('report_type_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->report_type_id), array('view', 'id'=>$data->report_type_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('report_name')); ?>:</b>
	<?php echo CHtml::encode($data->report_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('function_name')); ?>:</b>
	<?php echo CHtml::encode($data->function_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('report_category_id')); ?>:</b>
	<?php echo CHtml::encode($data->report_category_id); ?>
	<br />


</div>