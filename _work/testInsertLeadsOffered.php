<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
var_dump
(
	IcudeLeadsMaster::readListBy
	(
		[
			[
				'Start_Date', '2012-03-21', '>='
			]
		]
	)
);
?>