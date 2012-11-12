<?php
var_dump($_SERVER);
var_dump(getcwd());
$_SERVER['DOCUMENT_ROOT'] = 'foo';
var_dump($_SERVER['DOCUMENT_ROOT']);
?>