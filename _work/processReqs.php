<?php
require('../_inc/config.main.php');
$inputFileName = '2T 2012 OB SE Reporting Requirements 6.6 JP.xls';
LeadListRequirement::getNewRequirementsInFile($inputFileName);
$reqDocumentHeaders = LeadListRequirement::getAttributeHeadersFromFile($inputFileName);
$filters = 
[
	
];
$requirement = LeadListRequirement::getRequirementsFromFile($inputFileName);
?>