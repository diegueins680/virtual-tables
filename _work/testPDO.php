<?php
require ('../_inc/config.main.php');
/*var_dump(PDO::getAvailableDrivers());*/

try 
{
	$dbh = DBConnection::getLink('Indosoft');
	echo 'Connected to database';
}
catch(PDOException $e) 
{
	echo $e->getMessage();
}
var_dump($dbh);

/*
try
{
	$dbh2 = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\ProcessLeadDispo.mdb;Password=sunshine7139");
}
catch (PDOException $e)
{
	echo $e->getMessage();
}
var_dump($dbh2);

?>
*/

?>