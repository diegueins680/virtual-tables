<?php
/* @var $this CoxReportsController */
/* @var $model VCoxReportDetail */
/* @var $dataReportCategories ReportCategory */
$month = 'OCT';
$report_category_name = 'Lead List Summary';
//var_dump(Yii::app());

$count = Yii::app()->icude->createCommand()
	->select(array('count(*) as num'))
   // ->select('customer_name')
    ->from('report_category')
//    ->join('tbl_profile p', 'u.id=p.user_id')
    ->where('report_category_name=:report_category_name', array(':report_category_name'=>$report_category_name))
    ->queryRow();
var_dump($count);
?>
<div class="viewReportCategories">
<?php // echo CHtml::encode($dataReportCategories->getAttributeLabel('report_category_name')); ?>:</b>
	<?php // echo CHtml::link(CHtml::encode($dataReportCategories->report_category_name), array('viewReportCategories', 'id'=>$data->report_category_id)); ?>
</div>
<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('report_category_name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->report_category_name), array('view', 'id'=>$data->report_category_name)); /*?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vendor_id')); ?>:</b>
	<?php echo CHtml::encode($data->vendor_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('agent_id')); ?>:</b>
	<?php echo CHtml::encode($data->agent_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('util_type')); ?>:</b>
	<?php echo CHtml::encode($data->util_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('program_code')); ?>:</b>
	<?php echo CHtml::encode($data->program_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sales_state')); ?>:</b>
	<?php echo CHtml::encode($data->sales_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('auth_fname')); ?>:</b>
	<?php echo CHtml::encode($data->auth_fname); */ ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('auth_mi')); ?>:</b>
	<?php echo CHtml::encode($data->auth_mi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('auth_lname')); ?>:</b>
	<?php echo CHtml::encode($data->auth_lname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_fname')); ?>:</b>
	<?php echo CHtml::encode($data->bill_fname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_mi')); ?>:</b>
	<?php echo CHtml::encode($data->bill_mi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_lname')); ?>:</b>
	<?php echo CHtml::encode($data->bill_lname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company_name')); ?>:</b>
	<?php echo CHtml::encode($data->company_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company_title')); ?>:</b>
	<?php echo CHtml::encode($data->company_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('btn')); ?>:</b>
	<?php echo CHtml::encode($data->btn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serv_address')); ?>:</b>
	<?php echo CHtml::encode($data->serv_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serv_city')); ?>:</b>
	<?php echo CHtml::encode($data->serv_city); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serv_state')); ?>:</b>
	<?php echo CHtml::encode($data->serv_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serv_zip')); ?>:</b>
	<?php echo CHtml::encode($data->serv_zip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serv_county')); ?>:</b>
	<?php echo CHtml::encode($data->serv_county); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_address')); ?>:</b>
	<?php echo CHtml::encode($data->bill_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_city')); ?>:</b>
	<?php echo CHtml::encode($data->bill_city); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_state')); ?>:</b>
	<?php echo CHtml::encode($data->bill_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_zip')); ?>:</b>
	<?php echo CHtml::encode($data->bill_zip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('acct_type')); ?>:</b>
	<?php echo CHtml::encode($data->acct_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('acct_num')); ?>:</b>
	<?php echo CHtml::encode($data->acct_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meter_num')); ?>:</b>
	<?php echo CHtml::encode($data->meter_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rate_class')); ?>:</b>
	<?php echo CHtml::encode($data->rate_class); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lead_type')); ?>:</b>
	<?php echo CHtml::encode($data->lead_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_name_key')); ?>:</b>
	<?php echo CHtml::encode($data->customer_name_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_address')); ?>:</b>
	<?php echo CHtml::encode($data->email_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serv_ref_num')); ?>:</b>
	<?php echo CHtml::encode($data->serv_ref_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('epod_id')); ?>:</b>
	<?php echo CHtml::encode($data->epod_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tax_id')); ?>:</b>
	<?php echo CHtml::encode($data->tax_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idcrrt')); ?>:</b>
	<?php echo CHtml::encode($data->idcrrt); ?>
	<br />

	*/ ?>

</div>