<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$leadsArray = file('HOP_PECO Bus Phone Append_7-24-12.txt');
//var_dump($leadsArray);
/* @var $connection DBConnection */
foreach($leadsArray as $recordKey => $leadArray)
{
	if($recordKey > 0)
	{
		$record = explode("\t", $leadArray);
		$hopRecord = Hop::getInstance();
		$hopRecord->acct_num		= $record[1];
		$hopRecord->company_name	= $record[2];
		$hopRecord->bill_address	= $record[3];
		$hopRecord->bill_city 		= $record[6];
		$hopRecord->bill_state 		= $record[7];
		$hopRecord->bill_zip 		= $record[8];
		$hopRecord->serv_address	= $record[3];
		$hopRecord->serv_city 		= $record[6];
		$hopRecord->serv_state 		= $record[7];
		$hopRecord->serv_zip 		= $record[8];
		$hopRecord->rate_class		= $record[10];
		$hopRecord->btn 			= trim($record[12]);
		$hopRecord->insert();
	}
}
?>