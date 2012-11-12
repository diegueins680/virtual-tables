<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
//$filters = [['connection_identifier', 'Indosoft', '=']];
//$dbConfigs = DBConnectionConfig::readListBy($filters);
$dbConfigs = DBConnectionConfig::readListBy();
var_dump($dbConfigs);
?>