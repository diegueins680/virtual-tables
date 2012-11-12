<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$startDate = new DateTime(trim('2012-10-01'));
$listNameColumn = DBColumn::getInstance();
/* @var $listNameColumn DBColumn */
$params = new QueryParameter();
$params->db_connection = DBConnection::getByIdentifier('icude');
$params->baseTable = 'ht_leads_master';
$listNameColumn->setColumnName('List_name')->setTable('ht_leads_master')->setSchema('icude');
$params->filters = [[$listNameColumn, '%OCT%2012%', 'LIKE']];
//$params->additionalFields = [['ht_leads_master', 'List_name']];
/*$params->limit = 
[
	QueryParameter::LIMIT_FROM => 3,
	QueryParameter::LIMIT_TO =>10
];*/ 
/* @var $results PDOStatement */
var_dump(DataBaseAccessor::getSQL($params));
die;
$results = DataBaseAccessor::getResults($params);
var_dump($results->fetchAll());
die;
var_dump
(
	IcudeLeadsMaster::readListBy
	(
		[
			[
				$listNameColumn, '%OCT%2012%', 'LIKE'
			]
		],
		3
	)
);


?>