<?php
/* @var $this CallListController */
/* @var $model CallList */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('call_list_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->call_list_id), array('view', 'id'=>$data->call_list_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('callback_date_time')); ?>:</b>
	<?php echo CHtml::encode($data->callback_date_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('call_date_time')); ?>:</b>
	<?php echo CHtml::encode($data->call_date_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('campaign_id')); ?>:</b>
	<?php echo CHtml::encode($data->campaign_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('employee_id')); ?>:</b>
	<?php echo CHtml::encode($data->employee_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone_number')); ?>:</b>
	<?php echo CHtml::encode($data->phone_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('firstname')); ?>:</b>
	<?php echo CHtml::encode($data->firstname); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('lastname')); ?>:</b>
	<?php echo CHtml::encode($data->lastname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('e_mail_id')); ?>:</b>
	<?php echo CHtml::encode($data->e_mail_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('street')); ?>:</b>
	<?php echo CHtml::encode($data->street); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('st_or_province')); ?>:</b>
	<?php echo CHtml::encode($data->st_or_province); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<?php echo CHtml::encode($data->city); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postal_code')); ?>:</b>
	<?php echo CHtml::encode($data->postal_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_zone_id')); ?>:</b>
	<?php echo CHtml::encode($data->time_zone_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('leadcode')); ?>:</b>
	<?php echo CHtml::encode($data->leadcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('list_id')); ?>:</b>
	<?php echo CHtml::encode($data->list_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('priority')); ?>:</b>
	<?php echo CHtml::encode($data->priority); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_termination')); ?>:</b>
	<?php echo CHtml::encode($data->last_termination); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_inserted')); ?>:</b>
	<?php echo CHtml::encode($data->date_inserted); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('upload_id')); ?>:</b>
	<?php echo CHtml::encode($data->upload_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('callerid_num')); ?>:</b>
	<?php echo CHtml::encode($data->callerid_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('callerid_name')); ?>:</b>
	<?php echo CHtml::encode($data->callerid_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hold_time')); ?>:</b>
	<?php echo CHtml::encode($data->hold_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customerId')); ?>:</b>
	<?php echo CHtml::encode($data->customerId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('account_code')); ?>:</b>
	<?php echo CHtml::encode($data->account_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('locale_id')); ?>:</b>
	<?php echo CHtml::encode($data->locale_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('test_id')); ?>:</b>
	<?php echo CHtml::encode($data->test_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deleted')); ?>:</b>
	<?php echo CHtml::encode($data->deleted); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prev_termination')); ?>:</b>
	<?php echo CHtml::encode($data->prev_termination); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('temp_call_list_id')); ?>:</b>
	<?php echo CHtml::encode($data->temp_call_list_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('callable')); ?>:</b>
	<?php echo CHtml::encode($data->callable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('transfer_callerid_name')); ?>:</b>
	<?php echo CHtml::encode($data->transfer_callerid_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('transfer_callerid_num')); ?>:</b>
	<?php echo CHtml::encode($data->transfer_callerid_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('call_count')); ?>:</b>
	<?php echo CHtml::encode($data->call_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dial_count')); ?>:</b>
	<?php echo CHtml::encode($data->dial_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_dial_count')); ?>:</b>
	<?php echo CHtml::encode($data->max_dial_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_call_count')); ?>:</b>
	<?php echo CHtml::encode($data->max_call_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rule_id')); ?>:</b>
	<?php echo CHtml::encode($data->rule_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->phone_type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone_loaded_index')); ?>:</b>
	<?php echo CHtml::encode($data->phone_loaded_index); ?>
	<br />

	*/ ?>

</div>