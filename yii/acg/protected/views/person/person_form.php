<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'person-form-edit_person-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array(
                               'onsubmit'=>"return false;",/* Disable normal form submit */
                               'onkeypress'=>" if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                             ),
)); ?>

	
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($model,'age'); ?>
		<?php echo $form->textField($model,'age'); ?>
		<?php echo $form->error($model,'age'); ?>
	</div>


	<div class="row buttons">
	    <?php echo CHtml::Button('SUBMIT',array('onclick'=>'send();')); ?> 
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">

function send()
 {
   
   var data=$("#person-form-edit_person-form").serialize();


  $.ajax({
   type: 'POST',
    url: '<?php echo Yii::app()->createAbsoluteUrl("person/ajax"); ?>',
   data:data,
success:function(data){
                alert("succes:"+data); 
              },
   error: function(data) { // if error occured
         alert("Error occured.please try again");
         alert(data);
    },

  dataType:'html'
  });

}

</script>