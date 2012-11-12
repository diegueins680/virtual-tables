<?php
require ('../_inc/config.main.php');
var_dump
(
		$mySQL = MySQL::getInstanceBy
		(
			[
				[
					MySQL::CONNECTION_IDENT_VAR_NAME,
				]
			]
		)
);

?>