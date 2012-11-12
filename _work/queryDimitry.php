<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$params = new QueryParameter();
$params->baseTable = 'call_history';
$params->jointFields = 
[
	[
		[
			`campaign`
		],
		
	]
];
var_dump(DataBaseAccessor::getSQL($params));
?>