<?php
require ('../_inc/config.main.php');
/* @var $fieldFormat FieldFormat */
$fieldFormat = FieldFormat::getInstanceBy
(
		[
			[
				'fieldName',
				'clientName',
				'='
			]
		]
);
//$fieldFormat->fieldName = 'clientName';
var_dump($fieldFormat);
//$fieldFormat->insert();
?>