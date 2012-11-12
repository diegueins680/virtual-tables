<?php
require ('../_inc/config.main.php');
(
		Table::getInstanceBy
		(
				[
					[
						Table::TABLE_NAME,
						AppConst::TYPE_DBCONNECTION,
						'='
					]
				]
		)
);
?>