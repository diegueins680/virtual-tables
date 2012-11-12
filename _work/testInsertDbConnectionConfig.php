<?php
require ('../_inc/config.main.php');
/* @var $dbConnection DBConnectionConfig */
$dbConnection = DBConnectionConfig::getInstance();
/*
$dbConnection->connection_identifier = 'ProductionDB';
$dbConnection->db_connection_host = '192.168.1.131';
$dbConnection->db_connection_user = 'dba';
$dbConnection->db_connection_pass = 'acg3301';
$dbConnection->insert();
*/
$dbConnection->connection_identifier = 'Icude';
$dbConnection->db_connection_host = '192.168.1.235';
$dbConnection->db_connection_user = 'acg-admin';
$dbConnection->db_connection_pass = 'jasu5Red';
$dbConnection->db_connection_db_name = 'icude';
$dbConnection->db_driver = 'mysql';
$dbConnection->db_attributes = 'ATTR_ERRMODE=ERRMODE_EXCEPTION';
$dbConnection->db_options = 'PDO::MYSQL_ATTR_INIT_COMMAND=set names utf8';
$date = new DateTime();
$dbConnection->date_created = $date->format('Y-m-d h:m:s'); 
$dbConnection->insert();
?>