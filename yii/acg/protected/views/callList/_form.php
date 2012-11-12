<?php
/* @var $this CallListController */
/* @var $model CallList */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'call-list-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'callback_date_time'); ?>
		<?php echo $form->textField($model,'callback_date_time'); ?>
		<?php echo $form->error($model,'callback_date_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'call_date_time'); ?>
		<?php echo $form->textField($model,'call_date_time'); ?>
		<?php echo $form->error($model,'call_date_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'campaign_id'); ?>
		<?php echo $form->textField($model,'campaign_id'); ?>
		<?php echo $form->error($model,'campaign_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'employee_id'); ?>
		<?php echo $form->textField($model,'employee_id'); ?>
		<?php echo $form->error($model,'employee_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone_number'); ?>
		<?php echo $form->textField($model,'phone_number',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'firstname'); ?>
		<?php echo $form->textField($model,'firstname',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'firstname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastname'); ?>
		<?php echo $form->textField($model,'lastname',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'lastname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'e_mail_id'); ?>
		<?php echo $form->textField($model,'e_mail_id',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'e_mail_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'street'); ?>
		<?php echo $form->textField($model,'street',array('size'=>60,'maxlength'=>240)); ?>
		<?php echo $form->error($model,'street'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'st_or_province'); ?>
		<?php echo $form->textField($model,'st_or_province',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'st_or_province'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_zone_id'); ?>
		<?php echo $form->textField($model,'time_zone_id'); ?>
		<?php echo $form->error($model,'time_zone_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'leadcode'); ?>
		<?php echo $form->textField($model,'leadcode',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'leadcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'list_id'); ?>
		<?php echo $form->textField($model,'list_id'); ?>
		<?php echo $form->error($model,'list_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'priority'); ?>
		<?php echo $form->textField($model,'priority'); ?>
		<?php echo $form->error($model,'priority'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_termination'); ?>
		<?php echo $form->textField($model,'last_termination'); ?>
		<?php echo $form->error($model,'last_termination'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_inserted'); ?>
		<?php echo $form->textField($model,'date_inserted'); ?>
		<?php echo $form->error($model,'date_inserted'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'upload_id'); ?>
		<?php echo $form->textField($model,'upload_id'); ?>
		<?php echo $form->error($model,'upload_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'callerid_num'); ?>
		<?php echo $form->textField($model,'callerid_num',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'callerid_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'callerid_name'); ?>
		<?php echo $form->textField($model,'callerid_name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'callerid_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hold_time'); ?>
		<?php echo $form->textField($model,'hold_time'); ?>
		<?php echo $form->error($model,'hold_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customerId'); ?>
		<?php echo $form->textField($model,'customerId'); ?>
		<?php echo $form->error($model,'customerId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'account_code'); ?>
		<?php echo $form->textField($model,'account_code',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'account_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'locale_id'); ?>
		<?php echo $form->textField($model,'locale_id'); ?>
		<?php echo $form->error($model,'locale_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'test_id'); ?>
		<?php echo $form->textField($model,'test_id'); ?>
		<?php echo $form->error($model,'test_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deleted'); ?>
		<?php echo $form->textField($model,'deleted'); ?>
		<?php echo $form->error($model,'deleted'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'prev_termination'); ?>
		<?php echo $form->textField($model,'prev_termination'); ?>
		<?php echo $form->error($model,'prev_termination'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'temp_call_list_id'); ?>
		<?php echo $form->textField($model,'temp_call_list_id'); ?>
		<?php echo $form->error($model,'temp_call_list_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'callable'); ?>
		<?php echo $form->textField($model,'callable'); ?>
		<?php echo $form->error($model,'callable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'transfer_callerid_name'); ?>
		<?php echo $form->textField($model,'transfer_callerid_name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'transfer_callerid_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'transfer_callerid_num'); ?>
		<?php echo $form->textField($model,'transfer_callerid_num',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'transfer_callerid_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'call_count'); ?>
		<?php echo $form->textField($model,'call_count'); ?>
		<?php echo $form->error($model,'call_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dial_count'); ?>
		<?php echo $form->textField($model,'dial_count'); ?>
		<?php echo $form->error($model,'dial_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_dial_count'); ?>
		<?php echo $form->textField($model,'max_dial_count'); ?>
		<?php echo $form->error($model,'max_dial_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_call_count'); ?>
		<?php echo $form->textField($model,'max_call_count'); ?>
		<?php echo $form->error($model,'max_call_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rule_id'); ?>
		<?php echo $form->textField($model,'rule_id'); ?>
		<?php echo $form->error($model,'rule_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone_type_id'); ?>
		<?php echo $form->textField($model,'phone_type_id'); ?>
		<?php echo $form->error($model,'phone_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone_loaded_index'); ?>
		<?php echo $form->textField($model,'phone_loaded_index'); ?>
		<?php echo $form->error($model,'phone_loaded_index'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->