<?php
/* @var $this ReportTypeController */
/* @var $model ReportType */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'report_type_id'); ?>
		<?php echo $form->textField($model,'report_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'report_name'); ?>
		<?php echo $form->textField($model,'report_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'function_name'); ?>
		<?php echo $form->textField($model,'function_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'report_category_id'); ?>
		<?php echo $form->textField($model,'report_category_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->