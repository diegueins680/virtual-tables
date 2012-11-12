<?php
require ('../_inc/config.main.php');
var_dump(AttributeType::readListBy
(
		[
			[
				'table_id',
				11,
				'='
			]
		]
));
/*
$params = new QueryParameter();
$params->baseTable = AppConst::TABLE_ATTRIBUTE_TYPE;

$params->jointFields = 
$sql = DataBaseAccessor::getSQL($params);
*/
?>