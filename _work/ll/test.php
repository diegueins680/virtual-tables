<?php
//$fileName = 'R155022_052312_MW_NEWCUST_OUTPUT_ACG_ATT_Proprietary_Restricted_authorized_individuals_only.txt';
//$csvFileName = 'R155022_052312_MW_NEWCUST_RTN';
//set_time_limit(120);
//ini_set('memory_limit', '-1');
define('DS', '\\');
$dir = 'C:'.DS.'Documents and Settings'.DS.'diego.saa'.DS.'My Documents'.DS.'leads'.DS;

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
					if($file == 'NBA_ACG_August_2012_Final.txt')
					{
						if(strtolower(pathinfo($dir.DS.$file)['extension']) == 'txt')
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
}

function getSegments($params)
{
	if($params['flag5'] == 'COX')
	{
		return getCoxMarkets();
	}
}

function getCoxMarkets()
{
	return
	[
		'Virginia' => ['VA'],
		'Louisiana' => ['LA'], 
		'Florida_Georgia' => ['FL', 'GA'],
		'Oklahoma' => ['OK'],
		'KansasArkansas' => ['KS', 'AR'],
		'Las Vegas' => ['NV'],
		'New_England' => ['OH', 'CT', 'RI'],
		'Arizona' => ['AZ'],
		'Omaha' => ['NE']
	];
}

function writeCSV($fileName)
{
	$campPath = dirname($fileName);
	$camp = basename($campPath);
	$tab = basename(dirname($campPath));
	$region = basename(dirname(dirname($campPath)));
	$vendor = basename(dirname(dirname(dirname($campPath))));
	$path_parts = pathinfo($fileName);
	$csvFileName = $path_parts['filename'];
	xdebug_break();
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
		'flag4' => $region,
		'flag5' => $vendor,
		'tab' => $tab,
		'camp' => $camp,
		'separator' => "\t"
	];
	
	//xdebug_break();
	$campaignsForThisFile = getCampaignsForTab($tab);

	$leadsArray = file($fileName);

	$fieldFormats = getFieldFormats($params);

	$headersForImport = getHeadersForImport($leadsArray[0], $fieldFormats, $params);

	$leadsToImport = array();
	
	//$params['separator'] = "|";
	xdebug_break();
	$params['segments'] = getSegments($params);
	$fp = [];
	foreach($params['segments'] as $segmentKey => $segmentValue)
	{
		$fp[$segmentKey] = fopen($dir.$csvFileName.$segmentKey.'.csv', 'a');
	}
	
	foreach($leadsArray as $key => $lead)
	{
		if($key > 0)
		{
			$leadArray = explode($params['separator'], $lead);
			$leadArray = array_map('trim', $leadArray);
			xdebug_break();
			$leadToImport = getLeadToImport($leadArray, $headersForImport, $params);
			fputcsv($fp[getSegmentOfLead($leadToImport)], $leadToImport);
		}
	}
	fclose($fp[$segment]);
}

function getLeadToImport($leadArray, $importHeaders, $params)
{
	$leadToImport = array();
	foreach($importHeaders as $key => $importHeader)
	{
		$leadToImport[] = getFieldToImport($leadArray, $key, $importHeaders, $params);
	}
	return $leadToImport;
}

function getFieldToImport($leadArray, $key, $importHeaders, &$params = null)
{
	if($importHeaders[$key]['function'])
	{
		$params['function'] = $importHeaders[$key]['function'];
	}
	else
	{
		if(isset($params['function']))
		{
			unset($params['function']);
		}
	}
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
			$field = getFieldByPosition($leadArray, $key, $position, $params);
		}
	}
	else
	{
		$field = getFieldByDefault($importHeaders, $key, $leadArray, $params);
	}
	if($key == 'flag2')
	{
		$params['flag2'] = $field;
	}
	return $field;
}

function getFieldByPosition($leadArray, $key, $position, &$params)
{
	if(isset($params['function']))
	{
		$newParams = ['leadArray', 'position'];
		foreach($newParams as $param)
		{
			$params[$param] = $$param;
		}
		$field = $params['function']($params);
		
		foreach($newParams as $param)
		{
			unset($params[$param]);
		}
		unset($params['function']);
	}
	else
	{
		$field = $leadArray[$position];
	}
	return $field; 
}

function getFieldByDefault($headers, $key, $leadArray, &$params)
{
	$importHeader = $headers[$key];
	if(isset($importHeader['function']))
	{
		$field = $importHeader['function']($params);
	}
	elseif($importHeader['default'])
	{
		$field = $importHeader['default'];
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

function getHeadersForImport($fileHeadersLine, $fieldTypes, $params)
{
	$fileHeaders = explode($params['separator'], $fileHeadersLine);
	$fileHeaders = array_map('trim', $fileHeaders);
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
				if(isset($fieldTypes[$type]['possibleNames']))
				{
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
	}
	return $fieldTypes;
}

/**
 * 
 * @param unknown_type $params
 * @return array
 */
function getFieldFormats(&$params)
{
	
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
	$phoneFunction = function($params)
	{
		return substr($params['leadArray'][$params['position']], 0, 10);
	};
	$zipFunction = function($params)
	{
		return substr($params['leadArray'][$params['position']], 0, 5);
	};
	switch($params['flag5'])
	{
		case 'ASP':
		case 'ACG':
		case 'ACL':
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
			$returnArray =
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
					],
					'function' => $phoneFunction
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
					],
					'function' => $zipFunction
				],
				'flag1'  =>
				[
					'default' => $params['csvFileName']
				],
				'flag2'  =>
				[
					'possibleNames' =>
					[
						'LIST_SEGMENT_OTM'=> null,
						'LIST_SEGMENT_DM'=> null,
						'SEGMENT'=> null,
						'LEAD_SEGMENTATION_CD' => null
					],
					'default' => ''
				],
				'flag3' =>
				[
					'function' => $reqCampaignName
			//		'default' => $reqCampaignName($segmentName, $campaignArray)
				],
				'flag4'  =>
				[
					'possibleNames' => [],
					'default' => $params['flag4']
				],
				'flag5'  =>
				[
					'possibleNames' => [],
					'default' => $params['flag5']
				]
			];
		break;
		case 'COX':
			$concatenate = 
				function($params, $word)
				{
					return $word.': '.$params['leadArray'][$params['position']];
				};
			$returnArray = 
			[
				'clientName' =>
				[
			//'value' => $fieldValue,
					'possibleNames' =>
					[
						'BUSINESS_NAME'=> null
					]
				],
				'phoneNumber'  =>
				[
					'possibleNames' =>
					[
						'TELEPHONE' => null,
					],
					'function' =>
						$phoneFunction
				],
				'street' =>
				[
					'possibleNames' =>
					[
						'PHYSICAL_STREET_ADDRESS_LINE1'=> null
					]
				],
				'city' =>
				[
					'possibleNames' =>
					[
						'PHYSICAL_CITY'=> null
					]
				],
				'stateOrProvince'  =>
				[
					'possibleNames' =>
					[
						'PHYSICAL_STATE_ABBREVIATION'=> null
					]
				],
				'zip'  =>
				[
					'possibleNames' =>
					[
						'PHYSICAL_ZIP_US_ONLY' => null
					],
					'function' =>
						$zipFunction
				],
				'flag1'  =>
				[
					'possibleNames' =>
					[
						'DUNS_NUMBER'=> null
					],
					'function' => 
						function($params) use($concatenate)
						{
							return $concatenate($params, 'DUNS');
						}
				],
				'flag2'  =>
				[
					'possibleNames' =>
					[
						'DIST2TAP'=> null
					],
					'function' => 
						function($params)use($concatenate)
						{
							return $concatenate($params, 'TAP');
						}
				],
				'flag3' =>
				[
					'possibleNames' => 
					[
						'Market' => null
					],
					'function' => 
						function($params)use($concatenate)
						{
							return $concatenate($params, 'MKT');
						}
			//	'default' => $reqCampaignName($segmentName, $campaignArray)
				],
				'flag4'  =>
				[
					'possibleNames' => 
					[
						'SIC2Description' => null
					],
					'function' =>
						function($params)use($concatenate)
						{
							return $concatenate($params, 'SIC');
						}
				],
				'flag5'  =>
				[
					'possibleNames' => [],
					'default' => ''
				]
			];
			break;
	}
	return $returnArray;
}

function getCampaignsForTab($tab = null)
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
				'01' => 'ACCESSLINE PARTIAL WINBACK',
				'02' => 'DSL LD LOSS',
				'03' => 'DSL LD LOSS',
				'04' => 'LD LOSS'
			];
			break;
		case 'SPJ':
			return
			[
				'01' => 'IPBB POST INSTALL',
				'02' => 'IPBB POST INSTALL', 
				'03' => 'IPBB POST INSTALL'
			];
			break;
		default:
			return [];
			break;
	}
}
?>