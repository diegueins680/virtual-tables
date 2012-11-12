<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
/* @var $dbConnection DBConnection */
$dbConnection = DBConnection::getByIdentifier();
$sql = 'SELECT * FROM `table`   JOIN `attribute_type` ON (`table`.`table_id` = `attribute_type`.`table_id`)  WHERE (attribute_type.table_id = :table_id)';
$params = ['table_id' => '11'];
var_dump($dbConnection->executePreparedQuery($sql, $params));
/*
$array = [1,2,3];
$array=array_merge($array, $array);
var_dump($array);
$phone = '987-343-2354';
$phone = preg_replace('/\D+/', '', $phone);
var_dump($phone);
$company = "Marlow's Health Studio Inc.";
var_dump(htmlentities($company));
*/
?>