<?php
require ('../_inc/config.main.php');

$formats = 
[
	FieldFormat::getInstanceBy
	(
			[
				FieldFormatConst::NAME => FieldFormatConst::NAME,
				FieldFormatConst::POSSIBLE_HEADERS =>
				[
					FieldFormatConst::HEADER_ACCT_BILL_ADDR_BUSINESS_NM
				]
			],
			[
				FieldFormatConst::NAME => FieldFormatConst::VALUE_PHONE_NUMBER,
				FieldFormatConst::POSSIBLE_HEADERS => ['ACCT_BILLING_TELEPHONE_NBR', 'BILLING_TELEPHONE_NUMBER', 'PRIMARY_PHONE_NBR']
			],
			[
				FieldFormatConst::NAME => 'street',
				FieldFormatConst::POSSIBLE_HEADERS => ['ACCT_BILL_ADDR_PRIMARY_TXT', 'ACCT_BILL_ADDR_NM', 'ADDRESS'],
			],
			[
				FieldFormatConst::NAME => 'city',
				FieldFormatConst::POSSIBLE_HEADERS => ['ACCT_BILL_ADDR_CITY_NM', 'CITY']
			],
			[
				FieldFormatConst::NAME => 'stateOrProvince',
				FieldFormatConst::POSSIBLE_HEADERS => ['ACCT_BILL_ADDR_STATE_CD', 'STATE']
			],
			[
				FieldFormatConst::NAME => 'postalCode',
				FieldFormatConst::POSSIBLE_HEADERS => ['ACCT_BILL_ADDR_ZIP_CD', 'ZIP']
			],
			[
				FieldFormatConst::NAME => 'listName',
				'default' => ''
			],
			[
				FieldFormatConst::NAME => 'segment',
				FieldFormatConst::POSSIBLE_HEADERS =>  ['LIST_SEGMENT_DM', 'SEGMENT'],
				'default' => ''
			],
			[
				FieldFormatConst::NAME => 'reqCampaignName',
				'default' => ''
			],
			[
				FieldFormatConst::NAME => 'region',
				'default' => ''
			],
			[
				FieldFormatConst::NAME => 'vendor',
				'default' => ''
			]
	)
];

$formatTabSpaced = LeadListFormat::getInstance
(
		null,
		null,
		[
			LeadListFormatConst::NAME => LeadListFormatConst::FORMAT_TAB_SEPARATED,
			LeadListFormatConst::EXTENSION => LeadListFormatConst::FILE_EXTENSION_TXT,
			LeadListFormatConst::FIELD_FORMATS => $formats
		]
);
/* @var $formatTabSpaced LeadListFormat */
$formatTabSpaced->insert();
?>