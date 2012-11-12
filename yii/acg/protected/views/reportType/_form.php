<?php
/* @var $this ReportTypeController */
/* @var $model ReportType */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'report-type-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'report_name'); ?>
		<?php echo $form->textField($model,'report_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'report_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'function_name'); ?>
		<?php echo $form->textField($model,'function_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'function_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'report_category_id'); ?>
		<?php echo $form->textField($model,'report_category_id'); ?>
		<?php echo $form->error($model,'report_category_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->