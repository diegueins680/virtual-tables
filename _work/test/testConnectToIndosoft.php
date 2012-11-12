<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$dbCon = DBConnection::getInstance();
/* @var $dbCon DBConnection */
var_dump($dbCon);
?>