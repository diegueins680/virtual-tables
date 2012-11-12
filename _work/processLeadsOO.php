<?php 
require ('../_inc/config.main.php');
/* @var $list LeadList */
$completeFileName = 'SEBUS_WBLLSLLOTM_120727_ACG_AT&T_Proprietary_(Restricted)_authorized_individuals_only.TXT';
$info = pathinfo($completeFileName);
$fileName =  basename($completeFileName,'.'.$info['extension']);
$campaign = 'OB ACQ ALW WB';
$region = 'SE';
$vendor = 'ASP';
/* @var $format LeadListFormat */
$formatFixedWidthFiles = LeadListFormat::getInstance
(
		null, 
		null,
		[
			LeadListFormatConst::EXTENSION => 'txt',
			LeadListFormatConst::FIELD_FORMATS =>
			[
				'name' => 'clientName',
				'position' => 99, 
				'length' => 200
			],
			[
				'name' => 'phoneNumber', 
				'position' => 85, 
				'length' => 10
			],
			[
				'name' => 'street', 
				'position' => 451, 
				'length' => 50
			],
			[
				'name' => 'city', 
				'position' => 501, 
				'length' => 10
			],
			[
				'name' => 'stateOrProvince', 
				'position' => 540, 
				'length' => 2
			],
			[
				'name' => 'postalCode', 
				'position' => 531,
				'length' => 5
			],
			[
				'name' => 'listName', 
				'default' => $fileName.'.csv'
			],
			[
				'name' => 'segment', 
				'position' => 567, 
				'length' => 1
			],
			[
				'name' => 'reqCampaignName', 
				'default' => $campaign
			],
			[
				'name' => 'region', 
				'default' => $region
			],
			[
				'name' => 'vendor', 
				'default' => $vendor
			]
		]
);

$formatTabSpaced = LeadListFormat::getInstance
(
		null, null, array
		(
				LeadListFormatConst::EXTENSION => 'txt',
				LeadListFormatConst::FIELD_FORMATS => array
				(
						array
						(
								'name' => 'clientName',
								'possibleNames' => array('ACCT_BILL_ADDR_BUSINESS_NM')

						),
						array
						(
								'name' => 'phoneNumber',
								'possibleNames' => array('ACCT_BILLING_TELEPHONE_NBR', 'BILLING_TELEPHONE_NUMBER', 'PRIMARY_PHONE_NBR')

						),
						array
						(
								'name' => 'street',
								'possibleNames' => array('ACCT_BILL_ADDR_PRIMARY_TXT', 'ACCT_BILL_ADDR_NM', 'ADDRESS')

						),
						array
						(
								'name' => 'city',
								'possibleNames' => array('ACCT_BILL_ADDR_CITY_NM', 'CITY')
						),
						array
						(
								'name' => 'stateOrProvince',
								'possibleNames' => array('ACCT_BILL_ADDR_STATE_CD', 'STATE')

						),
						array
						(
								'name' => 'postalCode',
								'possibleNames' => array('ACCT_BILL_ADDR_ZIP_CD', 'ZIP')
						),
						array
						(
								'name' => 'listName',
								'default' => $fileName
						),
						array
						(
								'name' => 'segment',
								'possibleNames' =>  array('LIST_SEGMENT_DM', 'SEGMENT'),
								'default' => ''
						),
						array
						(
								'name' => 'reqCampaignName',
								'default' => $campaign
						),
						array
						(
								'name' => 'region',
								'default' => $region
						),
						array
						(
								'name' => 'vendor',
								'default' => $vendor
						)
				)
		)
);


//$formatDif = new LeadListFormat
//(
//array
//(
//new FieldFormat
//(
//array('name' => 'clientName', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//	array('name' => 'phoneNumber', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//array('name' => 'street', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//array('name' => 'city', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//array('name' => 'stateOrProvince', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//array('name' => 'postalCode', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//array('name' => 'listName', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//array('name' => 'segment', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//array('name' => 'reqCampaignName', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//array('name' => 'region', 'headerName' => 'Billing_Contact_Name')
//),
//new FieldFormat
//(
//array('name' => 'vendor', 'headerName' => 'Billing_Contact_Name')
//)
//)
//);

/* @var $leadCsv LeadCSV */
$leadCsv = LeadCSV::getInstance(
		null,
		null,
		array
		(
				LeadCsvConst::ORIGINATING_LEAD_LISTS => array($completeFileName),
				DataBaseAccessor::ATTRIBUTES => array(LeadCsvConst::FILE_NAME => $fileName)
		)
);
var_dump($leadCsv->generate());

?>