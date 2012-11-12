<?php
require ('../_inc/config.main.php');
class Foo extends DBConnection
{
}

$className = 'Foo';
/* @var $foo Foo */
$foo = $className::getInstance();

//$connection = MySQL::getInstance();
var_dump($foo);
//var_dump($connection);

?>