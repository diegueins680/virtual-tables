<?php
require ('../_inc/config.main.php');
$mySQL = MySQL::getInstance();
echo "object: ";
var_dump($mySQL);
//$qry = "SELECT * FROM `table`  WHERE (table_name = 'FieldFormat') LIMIT 1";
/* @var $mySQL MySQL */
//var_dump($mySQL->fetchAssoc($qry));
//var_dump($mySQL->getVirtualDbTable());
//mysql_fetch_assoc();
var_dump(ObjectFactory::getInstancesArray());
?>