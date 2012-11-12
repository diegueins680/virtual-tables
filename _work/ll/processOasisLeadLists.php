<?php
//$fileName = 'R155022_052312_MW_NEWCUST_OUTPUT_ACG_ATT_Proprietary_Restricted_authorized_individuals_only.txt';
//$csvFileName = 'R155022_052312_MW_NEWCUST_RTN';
//set_time_limit(120);
//ini_set('memory_limit', '-1');
define('DS', '\\');
$dir = 'C:'.DS.'Documents and Settings'.DS.'diego.saa'.DS.'My Documents'.DS.'oasisLeads'.DS;

processLeadListsInDirectory($dir);

function processLeadListsInDirectory($dir, $function = 'writeCSV')
{
	if (($dh = opendir($dir)) !== false)
	{
		while (($file = readdir($dh)) !== false)
		{
			if(!in_array($file, ['.', '..']))
			{
				if(is_dir($dir.DS.$file))
				{
					if(!in_array($file, ['processed', 'loaded']))
					{
						echo "file: ";
						var_dump($file);
						echo "is dir: ";
						var_dump($dir.DS.$file);
						processLeadListsInDirectory($dir.DS.$file);
					}
				}
				elseif(is_file($dir.DS.$file))
				{
					if(pathinfo($dir.DS.$file)['extension'] == 'txt')
					{
						echo "is file: ";
						var_dump($dir.DS.$file);
						$function($dir.DS.$file);
					}
				}
			}
		}
	}
}

function writeCSV($fileName)
{
	xdebug_break();
	$camp = '';
	$tab = '';
	$region = basename(dirname(dirname($campPath)));
	$vendor = basename(dirname(dirname(dirname($campPath))));
	$csvFileName = trim(basename($fileName), '.txt');
	
	$dir = './'.$vendor.'/'.$region.'/'.$tab.'/'.$camp.'/';
	if(!is_dir($dir))
	{
		mkdir ($dir, 0755, true);
	}
	
	echo "campPath:";
	var_dump($campPath);
	echo "region:";
	var_dump($region) ;
	echo "tab: ";
	var_dump($tab);
	echo "camp: ";
	var_dump($camp);
	
	$params =
	[
		'csvFileName' => $csvFileName,
		'region' => $region,
		'vendor' => $vendor,
		'tab' => $tab,
		'camp' => $camp
	];
	
	$campaignsForThisFile = getCampaignsForFile($fileName);

	$leadsArray = file($fileName);

	$fieldFormats = getFieldFormats($params);

	$headersForImport = getHeadersForImport($leadsArray[0], $fieldFormats);

	$leadsToImport = array();

	foreach($leadsArray as $key => $lead)
	{
		if($key > 0)
		{
			$leadsToImport[] = getLeadToImport($lead, $headersForImport, $params);
		}
	}
	$fp = fopen($dir.$csvFileName.'.csv', 'a');

	foreach ($leadsToImport as $lead)
	{
		fputcsv($fp, $lead);
	}

	fclose($fp);
}

function getLeadToImport($leadLine, $importHeaders, $params)
{
	$leadArray = explode("\t", $leadLine);
	$leadToImport = array();
	foreach($importHeaders as $key => $importHeader)
	{
		$leadToImport[] = getFieldToImport($leadArray, $key, $importHeaders, $params);
	}
	return $leadToImport;
}

function getFieldToImport($leadArray, $key, $importHeaders, $params = null)
{
	if(isset($importHeaders[$key]['possibleNames']))
	{
		if(count($importHeaders[$key]['possibleNames'])>0)
		{
			foreach($importHeaders[$key]['possibleNames'] as $header => $position)
			{
				if(!is_null($position))
				{
					break;
				}
			}
			return getFieldByPosition($leadArray, $key, $position, $params);
		}
	}
	return getFieldByDefault($importHeaders, $key, $leadArray, $params);
}

function getFieldByPosition($leadArray, $key, $position, $params)
{
	switch($key)
	{
		case 'zip':
			$field = substr($leadArray[$position], 0, 5);
			break;
		case 'phoneNumber':
			$field = substr($leadArray[$position], 0, 10);
			break;
		default:
			$field = $leadArray[$position];
			break;
	}	
	return $field; 
}

function getFieldByDefault($headers, $key, $leadArray, $params)
{
	$importHeader = $headers[$key];
	if(isset($importHeader['default']))
	{
		if(is_callable($importHeader['default']))
		{
			//if it's callable, it means that it's reqCampaignName
			$field = $importHeader['default'](getFieldToImport($leadArray, 'segment', $headers), getCampaignsForFile());
		}
		else
		{
			$field = $importHeader['default'];
		}
	}
	else
	{
		$field = '';
	}
	return $field;
}

function getIndexArray($fileHeaders, $headersForImport)
{
	$indexArray = array();
	foreach($fileHeaders as $index => $header)
	{
		foreach($headersForImport as $key => $importHeader)
		{
			if($header == $importHeader)
			{
				$indexArray[$key] = $index;
			}
		}
	}
	return $indexArray;
}

function array_search2d($needle, $haystack)
{
	foreach ($haystack as $key => $header)
	{
		if(count($header > 0))
		{
			foreach($needle as $n)
			{
				if ($needle == $header)
				{
					return $key;
				}
			}
		}
	}
	return false;
}

function getHeadersForImport($fileHeadersLine, $fieldTypes)
{
	$fileHeaders = explode("\t", $fileHeadersLine);
	foreach($fieldTypes as $type => $attributes)
	{
		if(isset($attributes['possibleNames']))
		{
			foreach($attributes['possibleNames'] as $possibleName => $position)
			{
				foreach($fileHeaders as $key => $header)
				{
					if($header == $possibleName)
					{
						$fieldTypes[$type]['possibleNames'][$header] = $key;
						unset($fileHeaders[$key]);
						break;
						
					}
				}
				foreach($fieldTypes[$type]['possibleNames'] as $processedName => $pos)
				{
					if(is_null($fieldTypes[$type]['possibleNames'][$processedName]))
					{
						unset($fieldTypes[$type]['possibleNames'][$processedName]);
					}					
				}
				if(count($fieldTypes[$type]['possibleNames'])<1)
				{
					unset($fieldTypes[$type]['possibleNames']);
				}
			}
		}
	}
	return $fieldTypes;
}

/**
 * 
 * @param unknown_type $params
 * @return array
 */
function getFieldFormats($params)
{
	$reqCampaignName = 
	function($segmentName, $campaigns) use (&$params)
	{
		if(array_key_exists($segmentName, $campaigns))
		{
			return 'OB '.$params['tab'].' '.$campaigns[$segmentName];
		}
		else
		{
			return 'OB '.$params['tab'].' '.$params['camp'];
		}
	};
	
	$fieldValue = 
	function($importHeader, $leadLineArray)
	{
		$fieldValuePos = null;
		foreach($importHeader as $key => $attributes)
		{
			foreach($attributes['possibleNames'] as $name => $position)
			{
				if(!empty($leadLineArray[$position]))
				{
					$fieldValuePos = $position;
				}
				
			}
		}
		return $fieldValuePos;
	};
	
	return
	[
		'clientName' =>
		[
			//'value' => $fieldValue,
			'possibleNames' =>
			[
				'ACCT_BILL_ADDR_BUSINESS_NM'=> null,
				'BILLED_ADDRESS_BUS_NAME' => null,
				'CONTACT_NM' => null, //the key is the name, and the value is the position
				'CONTACT_NAME' => null
				
			]
		],
		'phoneNumber'  =>
		[
			'possibleNames' =>
			[
				'ACCT_BILLING_TELEPHONE_NBR' => null,
				'BILLING_TELEPHONE_NUMBER'=> null,
				'BILLING_TELEPHONE_NBR' => null,
				'PRIMARY_PHONE_NBR'=> null
			]
		],
		'street' =>
		[
			'possibleNames' =>
			[
				'ACCT_BILL_ADDR_PRIMARY_TXT'=> null,
				'ACCT_BILL_ADDR_NM' => null,
				'BILLED_ADDRESS_PRIMARY' => null,
				'ADDRESS'=> null
			]
		],
		'city' =>
		[
			'possibleNames' =>
			[
				'ACCT_BILL_ADDR_CITY_NM'=> null,
				'BILLED_CITY' => null,
				'CITY'=> null
			]
		],
		'stateOrProvince'  =>
		[
			'possibleNames' =>
			[
				'ACCT_BILL_ADDR_STATE_CD'=> null,
				'BILLED_STATE_CD' => null,
				'STATE'=> null
			]
		],
		'zip'  =>
		[
			'possibleNames' =>
			[
				'ACCT_BILL_ADDR_ZIP_CD'=> null,
				'BILLED_ZIP_CODE' => null,
				'ZIP'=> null
			]
		],
		'listName'  =>
		[
			'possibleNames' =>
			[
				'155424_NBL_OTM'=> null
			],
			'default' => $params['csvFileName']
		],
		'segment'  =>
		[
			'possibleNames' =>
			[
				'LIST_SEGMENT_OTM'=> null,
				'LIST_SEGMENT_DM'=> null,
				'SEGMENT'=> null
			],
			'default' => ''
		],
		'reqCampaignName' =>
		[
			'default' => $reqCampaignName
				//'default' => $reqCampaignName($segmentName, $campaignArray)
		],
		'region'  =>
		[
			'possibleNames' => [],
			'default' => $params['region']
		],
		'vendor'  =>
		[
			'possibleNames' => [],
			'default' => $params['vendor']
		]
	];
}

function getCampaignsForFile($tab = null)
{
	switch($tab)
	{
		case 'RTN':
			return
			[
				'2' => 'RNWL TARGET CITIES AUG',
				'3' => 'RNWL CTXOTH AUG',
				'01C' => 'RNWL CTXOTH AUG',
				'AFLNC' => 'NEW CUST PROG AUG',
				'EVNC' => 'NEW CUST PROG AUG',
				'WNC' => 'NEW CUST PROG AUG'
				
				
			];
			break;
		case 'ACQ':
			return
			[
				'XGAT' => 'ALW WB',
				'XGMI' => 'ALW WB',
				'XODG' => 'ALW WB',
				'XODR' => 'ALW WB',
				'XONO' => 'ALW WB',
				'XRAT' => 'ALW WB',
				'XRMI' => 'ALW WB'
			];
			break;
		case 'PEN':
			return
			[
				'1'	=> 'ACCESSLINE PARTIAL WINBACK',
				'2' => 'DSL LD LOSS',
				'3'	=> 'DSL LD LOSS',
				'4' => 'LD LOSS'
			];
			break;
		case 'SPJ':
			return
			[
				'1' => 'IPBB POST INSTALL',
				'2' => 'IPBB POST INSTALL', 
				'3' => 'IPBB POST INSTALL'
			];
			break;
		default:
			return [];
			break;
	}
}
?>