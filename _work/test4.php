<?php
require ('../_inc/config.main.php');
//$type = 'MySQL';
//$identifier = AppConst::MYSQL_RW;
$mySQL = MySQL::getInstance
(
		null, 
		null, 
		[
			ObjectFactory::ATTRIBUTES =>
			[
				DBConnection::CONNECTION_IDENT_VAR_NAME => AppConst::MYSQL_RW
			]
		]
);
var_dump($mySQL);


//[
			//FieldFormatConst::ATTRIB_TYPE_NAME => FieldFormatConst::VALUE_NAME,
			//FieldFormatConst::ATTRIB_TYPE_NAME_POSSIBLE_HEADERS =>
			//[
//				'ACCT_BILL_ADDR_BUSINESS_NM'
			//]
		//]
?>