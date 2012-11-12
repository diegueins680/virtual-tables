<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
var_dump(DBConnectionConfig::getInstanceBy([['connection_identifier', 'Indosoft', '=']]));

?>