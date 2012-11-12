<?php
require ('../_inc/config.main.php');
$attributes = 
[
	ObjectFactory::ATTRIBUTES =>
	[
		FieldFormatConst::ATTRIB_TYPE_NAME => FieldFormatConst::VALUE_NAME,
		FieldFormatConst::ATTRIB_TYPE_NAME_POSSIBLE_HEADERS => 
		[
			FieldFormatConst::HEADER_ACCT_BILL_ADDR_BUSINESS_NM
		]
	]
];
$fieldFormat = FieldFormat::getInstance
(
		null, 
		null, 
		$attributes
);

/* @var $fieldFormat FieldFormat */
//var_dump($fieldFormat);

$fieldFormat->insertObj();

//var_dump($fieldFormat);

//$fieldFormat = FieldFormat::getInstanceBy
//(
//		[
//			FieldFormatConst::NAME => FieldFormatConst::VALUE_NAME,
//			FieldFormatConst::POSSIBLE_HEADERS => 
//			[
//				'ACCT_BILL_ADDR_BUSINESS_NM'
//			]
//		]
//);

//'name' => 'phoneNumber',
//'possibleNames' => array('ACCT_BILLING_TELEPHONE_NBR', 'BILLING_TELEPHONE_NUMBER', 'PRIMARY_PHONE_NBR')

//FieldFormat::getInstanceBy
//(
//		[
//		FieldFormatConst::NAME => FieldFormatConst::NAME,
//		FieldFormatConst::POSSIBLE_HEADERS =>
//		[
//		FieldFormatConst::HEADER_ACCT_BILL_ADDR_BUSINESS_NM
//		]
//		]
//);
?>