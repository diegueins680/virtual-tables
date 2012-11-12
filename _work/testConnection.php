<?php
require ('../_inc/config.main.php');
$connection = DBConnection::getInstance();
/* @var $connection DBConnection */
var_dump($connection);
var_dump($connection->getResource());
var_dump($connection);
?>