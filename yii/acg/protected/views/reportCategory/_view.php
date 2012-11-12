<?php
/* @var $this ReportCategoryController */
/* @var $data ReportCategory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('report_category_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->report_category_id), array('view', 'id'=>$data->report_category_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('report_category_name')); ?>:</b>
	<?php echo CHtml::encode($data->report_category_name); ?>
	<br />


</div>