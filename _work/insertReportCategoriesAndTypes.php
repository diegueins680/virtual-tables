<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$reportsArray = 
[
	'Activation Summary' =>
	[
		'Number of My Account Activations', 
		'Rates of My Account Activations against Live Contacts',
		'Number of Online Back-up Activations', 
		'Rates of Back-up Activations against Live Contacts',
		'Number of Security Activations', 
		'Rates of Security Activations against Live Contacts',
		'Number of Refer-a-biz intro’s',
		'Rates of Refer-a-biz intro’s against Live Contacts',
		'Number of Bill Reviews', 
		'Rates of Bill Reviews against Live Contacts',
	],
	'Sales Summary' => 
	[
		'Number of Proposals',
		'Number of Closed Won Sales',
		'Total Leads Dialed to Closed Won Ratio',
		'Live Contact Rate',
		'Live Contacts Reached to Proposal Ratio',
		'Proposal to Closed Won Ratio',
		'Total Sales MRC',
		'Annualized MRC'
	],
	'Compensation Summary' => 
	[
		'Talk Time (Hrs)',
		'Calls Dialed',
		'Total number of calls',
		'Hours (Talk Time)',
		'Total Calls/Hr (Attendance)',
		'Activation Average',
		'Call Quality Score Average',
	],
	'Misc' => 
	[
		'iTeam scores over 6',
		'Number of Warm Transfer',
		'Warm Transfer Group',
		'Warm Transfer Issues'
	]
];
//$dbConnection = DBConnection::getInstance();
/* @var $dbConnection DBConnection */
//$dbConnection->connect('Icude');
$reportCategoryDbAccessor = ConcreteDBAccessor::getInstance()
							->setDbConnectionIdentifier('Icude')
							->setDbTable('report_category')
							->setPKey('report_category_id');

$reportTypeDbAccessor = ConcreteDBAccessor::getInstance()
						->setDbConnectionIdentifier('Icude')
						->setDbTable('report_type')
						->setPKey('report_type_id');

$categoryNameColumn = DBColumn::getInstance()
						->setTable('report_category')
						->setColumnName('report_category_name');
foreach($reportsArray as $reportCategoryName => $reportTypes)
{
	$reportCategoryDbAccessor->read
	(
			[
				[
					$categoryNameColumn,
					$reportCategoryName,
					'='
				]
			]
	);
	var_dump($reportCategoryDbAccessor->getId());
	$reportTypeDbAccessor->set('report_category_id', $reportCategoryDbAccessor->getId());
	foreach($reportTypes as $reportType)
	{
		$reportTypeDbAccessor->set('report_name', $reportType);
							//->insert();
		var_dump($reportTypeDbAccessor);
	}
}
?>