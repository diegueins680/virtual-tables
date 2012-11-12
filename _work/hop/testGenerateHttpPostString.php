<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
$record = Hop::readListBy([[DBColumn::getInstance()->setColumnName('btn'), '2038387571', 'LIKE']])[0];
$ignoreFields = ['hop_data_id', 'add_id', 'idcrrt'];
$httpPostString = '';
foreach($record->getData() as $fieldKey => $field)
{
	if(is_string($fieldKey))
	{
		if(!in_array($fieldKey, $ignoreFields))
		{
			$httpPostString .= "'".urlencode($field)."',";
		}
	}
}
$httpPostString = 'http://www.dxc-inc.com/HOPEnergy?sql=execute dxc_sp_HOP_Energy_TM_Data_Insert '.$httpPostString;
$httpPostString = trim($httpPostString, ',');
$httpPostString.= '&root=dxc';
echo $httpPostString;
?>