<?php
/* @var $this HopDataController */
/* @var $model HopData */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hop-data-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'idhop_data_id'); ?>
		<?php echo $form->textField($model,'idhop_data_id', ['readonly'=>'readonly']); ?>
		<?php echo $form->error($model,'idhop_data_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vendor_id'); ?>
		<?php echo $form->textField($model,'vendor_id',['value'=>'20', 'size'=>3,'maxlength'=>3, 'readonly'=>'readonly']); ?>
		<?php echo $form->error($model,'vendor_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'agent_id'); ?>
		<?php
		if(empty($model->agent_id))
		{
			echo $form->textField($model,'agent_id', ['value'=>Yii::app()->user->getId(), 'size'=>10,'maxlength'=>10, 'readonly'=>'readonly']); 
		}
		else
		{
			echo $form->textField($model,'agent_id',['size'=>10,'maxlength'=>10, 'readonly'=>'readonly']);
		}
		echo $form->error($model,'agent_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'util_type'); ?>
		<div>
		<?php
		/*
		$concatenatedValue = '';
			function($values)
			{
				$concatenatedValue = '';
				foreach($values as $name => $value)
				{
					if($value == true)
					{
						$concatenatedValue.= $name;
					}
					$concatenatedValue.='|';
				}
				$concatenatedValue = trim($concatenatedValue, '|');
			};
		$utilType = explode('|', $model->util_type);
		var_dump($utilType);*/
		?>
		<?php /* echo $form->checkBox($model,'util_type',['name' => 'electric', 'value'=>'ELECTRIC']); */?>
		</div>
		<div>
		<?php /* echo $form->checkBox($model,'util_type',['name' => 'gas', 'value'=>'GAS']); */ ?>
		</div>
		<?php echo $form->textField($model,'util_type',['size'=>60,'maxlength'=>200, 'readonly'=>'readonly', 'value'=>'ELECTRIC']);?>
		<?php echo $form->error($model,'util_type'); ?>
	</div>
	<div class="row">
	<script>
			function getRateByProgram(program)
{
switch(program)
{
case '703':
  return '30'
  break;
case '704':
  return 'GS'
  break;
case '705':
  return '035'
  break;
case '706':
  return 'GST'
  break;
default:
return 'error'
break;
}
}

function displayRate()
{
document.getElementById("HopData_rate_class").value = getRateByProgram(document.getElementById("HopData_program_code").value);
}
</script>		
		<?php echo $form->labelEx($model,'program_code'); ?>
		<?php echo $form->dropDownList
		(
				$model,
				'program_code',
				[
					null => '',
					'703'=>'703 (CLP Rate 30)', 
					'704'=>'704 (UI Rate GS)', 
					'705'=>'705 (CLP Rate 035)', 
					'706'=>'706 (UI Rate GST)'
				],
				[
					'onchange'=>'displayRate()'
				]
		); ?>
		<?php echo $form->error($model,'program_code'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'rate_class'); ?>
		<?php echo $form->textField($model,'rate_class',array('size'=>60,'maxlength'=>70, 'readonly'=>'readonly')); ?>
		<?php echo $form->error($model,'rate_class'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sales_state'); ?>
		<?php echo $form->textField($model,'sales_state',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'sales_state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'auth_fname'); ?>
		<?php echo $form->textField($model,'auth_fname',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'auth_fname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'auth_mi'); ?>
		<?php echo $form->textField($model,'auth_mi',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'auth_mi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'auth_lname'); ?>
		<?php echo $form->textField($model,'auth_lname',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'auth_lname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_fname'); ?>
		<?php echo $form->textField($model,'bill_fname',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'bill_fname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_mi'); ?>
		<?php echo $form->textField($model,'bill_mi',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'bill_mi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_lname'); ?>
		<?php echo $form->textField($model,'bill_lname',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'bill_lname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'company_name'); ?>
		<?php echo $form->textField($model,'company_name',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'company_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'company_title'); ?>
		<?php echo $form->textField($model,'company_title',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'company_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'btn'); ?>
		<?php echo $form->textField($model,'btn',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'btn'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serv_address'); ?>
		<?php echo $form->textField($model,'serv_address',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'serv_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serv_city'); ?>
		<?php echo $form->textField($model,'serv_city',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'serv_city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serv_state'); ?>
		<?php echo $form->textField($model,'serv_state',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'serv_state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serv_zip'); ?>
		<?php echo $form->textField($model,'serv_zip',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'serv_zip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serv_county'); ?>
		<?php echo $form->textField($model,'serv_county',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'serv_county'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_address'); ?>
		<?php echo $form->textField($model,'bill_address',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'bill_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_city'); ?>
		<?php echo $form->textField($model,'bill_city',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'bill_city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_state'); ?>
		<?php echo $form->textField($model,'bill_state',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'bill_state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_zip'); ?>
		<?php echo $form->textField($model,'bill_zip',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'bill_zip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'acct_type'); ?>
		<?php echo $form->dropDownList
		(
				$model,
				'acct_type',
				[
					null => '',
					'POD ID'=>'POD ID', 
					'ACCT NUM'=>'ACCT NUM'
				]		
		); ?>
		<?php echo $form->error($model,'acct_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'acct_num'); ?>
		<?php echo $form->textField($model,'acct_num',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'acct_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'meter_num'); ?>
		<?php echo $form->textField($model,'meter_num',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'meter_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lead_type'); ?>
		<?php echo $form->textField($model,'lead_type',array('size'=>60,'maxlength'=>250, 'readonly'=>'readonly')); ?>
		<?php echo $form->error($model,'lead_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_name_key'); ?>
		<?php echo $form->textField($model,'customer_name_key',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'customer_name_key'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_address'); ?>
		<?php echo $form->textField($model,'email_address',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serv_ref_num'); ?>
		<?php echo $form->textField($model,'serv_ref_num',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'serv_ref_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'epod_id'); ?>
		<?php echo $form->textField($model,'epod_id',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'epod_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tax_id'); ?>
		<?php echo $form->textField($model,'tax_id',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'tax_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->