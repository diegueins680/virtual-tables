<?php
/* @var $this ReportCategoryController */
/* @var $model ReportCategory */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'report_category_id'); ?>
		<?php echo $form->textField($model,'report_category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'report_category_name'); ?>
		<?php echo $form->textField($model,'report_category_name',array('size'=>55,'maxlength'=>55)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->