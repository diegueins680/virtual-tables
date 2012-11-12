<?php
/* @var $this CallListController */
/* @var $model CallList */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'call_list_id'); ?>
		<?php echo $form->textField($model,'call_list_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'callback_date_time'); ?>
		<?php echo $form->textField($model,'callback_date_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'call_date_time'); ?>
		<?php echo $form->textField($model,'call_date_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'campaign_id'); ?>
		<?php echo $form->textField($model,'campaign_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'employee_id'); ?>
		<?php echo $form->textField($model,'employee_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone_number'); ?>
		<?php echo $form->textField($model,'phone_number',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'firstname'); ?>
		<?php echo $form->textField($model,'firstname',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lastname'); ?>
		<?php echo $form->textField($model,'lastname',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'e_mail_id'); ?>
		<?php echo $form->textField($model,'e_mail_id',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'street'); ?>
		<?php echo $form->textField($model,'street',array('size'=>60,'maxlength'=>240)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'st_or_province'); ?>
		<?php echo $form->textField($model,'st_or_province',array('size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_zone_id'); ?>
		<?php echo $form->textField($model,'time_zone_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'leadcode'); ?>
		<?php echo $form->textField($model,'leadcode',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'list_id'); ?>
		<?php echo $form->textField($model,'list_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'priority'); ?>
		<?php echo $form->textField($model,'priority'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'last_termination'); ?>
		<?php echo $form->textField($model,'last_termination'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_inserted'); ?>
		<?php echo $form->textField($model,'date_inserted'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'upload_id'); ?>
		<?php echo $form->textField($model,'upload_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'callerid_num'); ?>
		<?php echo $form->textField($model,'callerid_num',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'callerid_name'); ?>
		<?php echo $form->textField($model,'callerid_name',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hold_time'); ?>
		<?php echo $form->textField($model,'hold_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'customerId'); ?>
		<?php echo $form->textField($model,'customerId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'account_code'); ?>
		<?php echo $form->textField($model,'account_code',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'locale_id'); ?>
		<?php echo $form->textField($model,'locale_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'test_id'); ?>
		<?php echo $form->textField($model,'test_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deleted'); ?>
		<?php echo $form->textField($model,'deleted'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'prev_termination'); ?>
		<?php echo $form->textField($model,'prev_termination'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'temp_call_list_id'); ?>
		<?php echo $form->textField($model,'temp_call_list_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'callable'); ?>
		<?php echo $form->textField($model,'callable'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'transfer_callerid_name'); ?>
		<?php echo $form->textField($model,'transfer_callerid_name',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'transfer_callerid_num'); ?>
		<?php echo $form->textField($model,'transfer_callerid_num',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'call_count'); ?>
		<?php echo $form->textField($model,'call_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dial_count'); ?>
		<?php echo $form->textField($model,'dial_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_dial_count'); ?>
		<?php echo $form->textField($model,'max_dial_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_call_count'); ?>
		<?php echo $form->textField($model,'max_call_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rule_id'); ?>
		<?php echo $form->textField($model,'rule_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone_type_id'); ?>
		<?php echo $form->textField($model,'phone_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone_loaded_index'); ?>
		<?php echo $form->textField($model,'phone_loaded_index'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->