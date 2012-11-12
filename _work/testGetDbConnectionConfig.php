<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$filters = [['connection_identifier', 'Indosoft', '=']];
$dbConfig = DBConnectionConfig::readListBy($filters);
//$dbConfigs = DBConnectionConfig::readListBy();
//$dbConfig = 
var_dump($dbConfig);
?>