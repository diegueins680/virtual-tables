<?php
require ('../_inc/config.main.php');
/* @var $table Table */
$table = Table::getInstance();
$table->table_name = 'file_format';
$table->read([['table_name', 'file_format', '=']]);
var_dump($table);

?>