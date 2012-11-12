<?php
require($_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php');

$inputFileName = 'C:\Documents and Settings\diego.saa\My Documents\Requirements\MW\2T 2012 OB MW Reporting Requirements 8.0 JP.xls';

//$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
$inputFileType = 'Excel5';
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($inputFileName);
$requirementsArray = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

/* @var $llRequirement LeadListRequirement */
$llRequirement = LeadListRequirement::getInstance();




$llReqTableId = $llRequirement->getVirtualDbTable()->getId();
/*
 * The following creates the attribute types for ll_requirements
*/

var_dump($requirementsArray[2]);
//die;
/* The next inserts all the headers in the requirements document */
foreach($requirementsArray[2] as $keyAttrib => $requirementAttribute)
{
	/* @var $llReqAttrib LeadListRequirementAttrib */
	$llReqAttrib = AttributeType::getInstanceBy
	(
			[
				[
					'table_id',
					$llReqTableId,
					'='
				],
				[
					'attribute_type_name',
					$requirementAttribute,
					'='
				]
			]
	);
	if(!$llReqAttrib->exists())
	{
		$llReqAttrib->table_id = $llReqTableId;
		$llReqAttrib->attribute_type_name = $requirementAttribute;
		$llReqAttrib->data_type = 'varchar';
		$llReqAttrib->unique = false;
		$llReqAttrib->nullable = true;
		$llReqAttrib->pkey = false;
		$llReqAttrib->insert();
	}		
}
 /*
foreach($requirementsArray as $keyReq => $requirement)
{
	if($keyReq == 2)
	{
		foreach($requirement as $keyAttrib => $requirementAttribute)
		{
			$attributeType = AttributeType::getInstance();
			/* @var $attributeType AttributeType */
/*			$attributeType->$requirementAttribute
		}
	}
}
	/*
	if($keyReq == 3)
	{
		$llRequirement = LeadListRequirement::getInstance();
		foreach($requirement as $keyAttrib => $requirementAttribute)
		{
			$llRequirement->
		}
	}	
}
var_dump($llRequirement);*/
?>