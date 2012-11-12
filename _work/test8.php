<?php
require ('../_inc/config.main.php');
$params = new QueryParameter();
$baseTable = Table::getInstance();
$params->baseTable = $baseTable;
/* @var $baseTable Table */
$params->jointFields = 
[
	[
		[
			[
				AppConst::TABLE_TABLE,
				Table::TABLE_ID
			],
			[
				AppConst::TABLE_ATTRIBUTE_TYPE,
				Table::TABLE_ID
			]
		]
	]
];
foreach(DataBaseAccessor::getResults($params) as $resV)
{
	var_dump($resV);
}
?>