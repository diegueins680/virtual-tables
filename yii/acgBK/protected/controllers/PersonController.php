<?php

class PersonController extends CController
{
  
 public $layout="main";

 public function actionAjax()
 {
    $model=new PersonForm;

   
    // uncomment the following code to enable ajax-based validation
    
    if(isset($_POST['ajax']) && $_POST['ajax']==='person-form')
    {
        echo CActiveForm::validate($model);
        Yii::app()->end();
    }
    
        


    if(isset($_POST['PersonForm']))
    {
       
          
        $model->attributes=$_POST['PersonForm'];
        if($model->validate())
        {
           // form inputs are valid, do something here
           print_r($_REQUEST);
           return;
                    
        
         
        }
    }
    $this->render('person_form',array('model'=>$model));

 }

}
?>