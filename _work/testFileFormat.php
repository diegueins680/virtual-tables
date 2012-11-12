<?php
require ('../_inc/config.main.php');
/* @var $fieldFormat FieldFormat */
$fileFormat = FileFormat::getInstance();
$fileFormat->fileFormatName = 'fixedWidth';
//$fieleFormat->fieldName = 'clientName';
//var_dump($fieldFormat);
$fileFormat->insert();
?>