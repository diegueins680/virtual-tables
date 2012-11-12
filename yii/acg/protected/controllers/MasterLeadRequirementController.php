<?php
require ($_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php');
class MasterLeadRequirementController extends Controller
{
	public $requirementsArray;
	
	public function setRequiremetsArray($requirements)
	{
		
	}
	
	public function actionIndex()
	{
		error_reporting(E_ALL);
		set_time_limit(0);
		date_default_timezone_set('America/New_York');
		$inputFileName = 'C:\Documents and Settings\diego.saa\My Documents\Requirements\MW\2T 2012 OB MW Reporting Requirements 8.0 JP.xls';
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		$this->requirementsArray = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$this->render
		(
				'index', 
				[
					'requirements' => $this->requirementsArray
				]
		);
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}