<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$leadsArray = file('files/HOP_UI Commercial Leads_100511.txt');
/* @var $connection DBConnection */
foreach($leadsArray as $recordKey => $leadArray)
{
	if($recordKey > 0)
	{
		$record = explode("\t", $leadArray);
		$hopRecord = Hop::getInstance();
		$hopRecord->company_name	= $record[0];
		$hopRecord->bill_address	= $record[1];
		$hopRecord->bill_city 		= $record[2];
		$hopRecord->bill_state 		= $record[3];
		$hopRecord->bill_zip 		= $record[4];
		$hopRecord->idcrrt 			= $record[5];		
		$hopRecord->btn 			= preg_replace('/\D+/', '', $record[12]);
		$hopRecord->bill_fname 		= $record[15];
		$hopRecord->bill_lname 		= $record[16];
		$hopRecord->insert(null, true);
	}
}
?>