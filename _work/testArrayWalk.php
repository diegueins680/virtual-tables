<?php
function multiply($num, $factor)
{
	return $num * $factor;
}
$array = [1,2,3,4,5];
$resultingArray = array_map('multiply', $array, [3,3,3,3,3]);
var_dump($resultingArray);
?>