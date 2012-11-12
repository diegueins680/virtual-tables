<?php
function getAppender($baseString)
{
	return function($appendString) use ($baseString) {
		return $baseString .
		$appendString;
	};
}
$apender = getAppender('prueba');
echo $apender('otraCosa');
?>