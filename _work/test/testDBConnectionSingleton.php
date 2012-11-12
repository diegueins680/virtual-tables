<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
/* @var $link PDO */
$link = DBConnection::getByIdentifier('Indosoft');
var_dump($link);
$sql = 'SELECT * FROM cti.call_history WHERE 1=1 ORDER BY call_history_id DESC LIMIT 0, 5';

var_dump($link->query($sql));
foreach ($link->query($sql) as $row) 
{
	var_dump($row);
}
?>