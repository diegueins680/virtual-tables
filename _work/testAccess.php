<?php
require ('../_inc/config.main.php');
/*** Some ADO Constants ***/
//$adInteger = 3;
$adVarChar = 200;
$adParamInput = 1;
$adOpenStatic = 3;
$adLockReadOnly = 1;
$adCmdText = 1;

/*** Open ADODB command, connection, and recordset objects ***/
$cmd = new COM('ADODB.Command') or exit ('Cannot start ADO Command Object.');
var_dump($cmd);
$conn = new COM('ADODB.Connection') or exit('Cannot start ADO Connection Object.');
$rs = new COM('ADODB.Recordset') or exit('Cannot start ADO Recordset Object.');
$rs2 = new COM('ADODB.Recordset') or exit('Cannot start ADO Recordset Object.');

$conn->Open("Provider=Microsoft.Jet.OLEDB.4.0; Data Source=C:\\ProcessLeadDispo.mdb");
if (!$conn) {
	exit('Cannot open database');
}

//$conn->SetFetchMode(ADODB_FETCH_NUM);
//$rs1 = $db->Execute('select * from raw_import_file');
//$db->SetFetchMode(ADODB_FETCH_ASSOC);
//$rs2 = $db->Execute('select * from raw_import_file');

//print_r($rs1->fields); # shows array([0]=>'v0',[1] =>'v1')
//print_r($rs2->fields); # shows array(['col1']=>'v0',['col2'] =>'v1')

//$selectCommand="SELECT * FROM raw_import_file";
//$results = $conn->Execute($selectCommand);

//var_dump($results);
//foreach($results as $result)
//{
//	var_dump($result);
//}

?>