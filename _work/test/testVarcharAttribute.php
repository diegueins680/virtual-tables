<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$attributes = [];
$attributes = array_merge($attributes, VarcharAttribute::readListBy([['varchar_attribute_value', 'Indosoft', '=']]));
var_dump($attributes);

?>