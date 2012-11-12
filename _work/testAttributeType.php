<?php
require ('../_inc/config.main.php');
/* @var $attributeType AttributeType */
/* @var $fieldFormat FieldFormat */
/*
$fieldFormat = FieldFormat::getInstance();
$attributeType = AttributeType::getInstance();
$attributeType->table_id = $fieldFormat->getVirtualDbTable()->getId();
$attributeType->attribute_type_name = 'fieldName';
$attributeType->data_type = 'varchar';
$attributeType->unique = 1;
$attributeType->nullable = 0;
$attributeType->pkey = 1;
*/

/*
$fieldFormat = FieldFormat::getInstance();
$attributeType = AttributeType::getInstance();
$attributeType->table_id = $fieldFormat->getVirtualDbTable()->getId();
$attributeType->attribute_type_name = 'table_id';
$attributeType->data_type = 'object';
$attributeType->unique = 0;
$attributeType->nullable = 1;
$attributeType->pkey = 0;

$attributeType->insert();
*/

$fileFormat = FileFormat::getInstance();
$attributeType = AttributeType::getInstance();
$attributeType->create('FileFormat', 'fileFormatName', 'varchar', false, true, false); 
var_dump($attributeType);

//$attributeType->insert();


?>