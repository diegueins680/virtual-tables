<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);
//$inputFileName = 'reqs.xls';
//$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
//$requirementsArray = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$params = 
[
	'segmentBy' => 'flag2',
	'dateReceived' => '30-Aug',
	'camp' => 'Bus ALWB NBM Weekly Fresh Defectors OTM',
	'region' => 'WE',
	'vendor' => 'XAC',
	'tab' => 'ACQ'
];
$file = 'ACG_DATA_20120830.TXT';
/*
$params = 
[
	'segmentBy' => 'flag2',
	'dateReceived' => '21-Aug',
	'camp' => 'ALW 2DAY FD',
	'region' => 'SE',
	'vendor' => 'ASP',
	'tab' => 'ACQ'
];
$file = 'SEBUS_WBFDOTM_RLM120821_ACG_AT&T_Proprietary_(Restricted)_authorized_individuals_only.txt';
*/
//
//$params['dateReceived'] = '30-Aug';
//$params['camp'] = 'ACCESSLINE PARTIAL WINBACK';
//$params['region'] = 'SE';
//$params['vendor'] = 'ACG';
//$params['tab'] = 'PEN';
/*
$params['segmentBy'] = 'flag2';
$params['dateReceived'] = trim($requirementsArray[2]['C']);
$params['camp'] = trim($requirementsArray[2]['Z']);
$params['region'] = trim($requirementsArray[2]['A']);
$params['vendor'] = substr(trim($requirementsArray[2]['E']),0,3);
$params['tab'] = trim($requirementsArray[2]['Y']);
$file = $requirementsArray[2]['M'];
*/
var_dump($params);
//var_dump($requirementsArray);

function getCampaignsWithFixedWidthFormat()
{
	return 
	[
		'IPBB POST INSTALL', 
		'ALW 2DAY FD', 
		'NBM ACQ ALW 2DAY FD',
		'SBS NO TRAFFIC'
	];
}

function &removeUnwantedStrings($fileName, array $unwantedStrings) 
{
	foreach($unwantedStrings as $string)
	{
		$fileName = trim($fileName, $string);
	}
	return $fileName;
}
$unwantedStrings = 
[
	' ',
	'.pgp',
	'.PGP'
];			
$file = removeUnwantedStrings($file, $unwantedStrings);
//$file = 'files/SE_FD_ACG_082912.TXT';
var_dump($file);
//var_dump($requirementsArray);
writeCSV('files/'.$file, $params);

function getSegments($params)
{
	if($params['flag5'] == 'COX')
	{
		if($params['camp'] != 'NBA')
		{
			$segments = false;
		}
		else
		{
			$segments = getCoxMarkets();
		}
	}
	else
	{
		$segments = false;
	}
	return $segments;
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
	'Las_Vegas' => ['NV'],
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
	$dir = './'.$vendor.'/'.$region.'/'.$tab.'/'.$camp.'/';
	if(!is_dir($dir))
	{
		mkdir ($dir, 0755, true);
	}

	$leadsArray = file($fileName);

	$params =
	[
	'csvFileName' => $csvFileName,
	'flag4' => $region,
	'flag5' => $vendor,
	'tab' => $tab,
	'camp' => $camp,
	'campaigns' => getCampaignsForTab($tab),
	'delimiter' => detectDelimiter($fileName),
	];
	$params['segments'] = getSegments($params);

	$fieldFormats = getFieldFormats($params);
	//$fieldFormats = getRestOfHeaders($leadsArray[0], $fieldFormats);

	$headersForImport = getHeadersForImport($leadsArray[0], $fieldFormats, $params);

	$leadsToImport = array();

	$fp = [];

	if($params['segments'])
	{
		foreach($params['segments'] as $segmentKey => $segmentValue)
		{
			$fp[$segmentKey] = fopen($dir.$csvFileName.$segmentKey.'.csv', 'a');
		}
	}
	else
	{
		$fp[''] = fopen('451.csv', 'a');;
	}
	$i = 0;
	foreach($leadsArray as $keyLead => $lead)
	{
		if($keyLead == 0)
		{
			if(isset($headersForImport['hasHeaders']))
			{
				if($headersForImport['hasHeaders'])
				{
					continue;
				}				
			}
		}
		if(isset($headersForImport['fixedWidth']))
		{
			if($headersForImport['fixedWidth'])
			{
				$params['fixedWidth'] = true;
				$leadToImport = getLeadToImport($lead, $headersForImport, $params);
			}
		}
		else
		{

			$leadArray = explode($params['delimiter'], $lead);
			$leadArray = array_map('trim', $leadArray);
			$leadToImport = getLeadToImport($leadArray, $headersForImport, $params);
		}
		fputcsv($fp[getLeadSegment($params)], $leadToImport);
		$i++;
		if(isset($params['function']))
		{
			unset($params['function']);
		}
	}
	foreach($fp as $linkKey => $link)
	{
		fclose($fp[$linkKey]);
	}
	echo "wrote ".$i." leads to ".$dir.$csvFileName.".csv</p>";
	return $i;
}

function getLeadSegment($params)
{
	if($params['segments'])
	{
		foreach($params['segments'] as $segmentKey => $states)
		{
			if(in_array($params['state'], $states))
			{
				break;
			}

		}
	}
	else
	{
		$segmentKey = '';
	}
	return $segmentKey;
}

function getLeadToImport($leadArray, $importHeaders, &$params)
{
	$leadToImport = array();
	foreach($importHeaders['fields'] as $key => $importHeader)
	{
		if(isset($params['fixedWidth']))
		{
			if($params['fixedWidth'])
			{
				if(isset($importHeaders['fields'][$key]['length']))
				{
					$params['length'] = $importHeaders['fields'][$key]['length'];
				}
			}
		}
		$field = getFieldToImport($leadArray, $key, $importHeaders['fields'], $params);
		if($key  == 'stateOrProvince')
		{
			$params['state'] = $field;
		}
		$leadToImport[] = $field;
	}
	return $leadToImport;
}

function getFieldToImport($leadArray, $key, $importHeaders, &$params = null)
{
	if(isset($importHeaders[$key]['function']))
	{
		if($importHeaders[$key]['function'])
		{
			$params['function'] = $importHeaders[$key]['function'];
		}
		else
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
		if(isset($importHeaders[$key]['position']))
		{
			if(is_null($importHeaders[$key]['position']))
			{
				$field = getFieldByDefault($importHeaders, $key, $leadArray, $params);
			}
			else
			{
				$field = getFieldByPosition($leadArray, $key, $importHeaders[$key]['position'], $params);
			}
		}
		else
		{
			$field = getFieldByDefault($importHeaders, $key, $leadArray, $params);
		}
	}
	if($key == 'flag2')
	{
		$params['flag2'] = $field;
	}
	if(isset($importHeaders[$key]['sanitize']))
	{
		$field = preg_replace("/[^A-Za-z0-9 ]/", '', $field);
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
		if(isset($leadArray[$position]))
		{
			if(isset($params['fixedWidth']))
			{
				if($params['fixedWidth'])
				{
					$field = substr($leadArray, $position, $params['length']);
				}
			}
			else
			{
				$field = (string)$leadArray[$position];
			}
		}
		else
		{
			$field = '';
		}
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
	elseif(isset($importHeader['default']))
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
	if($fieldTypes['hasHeaders'])
	{
		$fileHeaders = explode($params['delimiter'], $fileHeadersLine);
		$fileHeaders = array_map('trim', $fileHeaders);
		foreach($fieldTypes['fields'] as $type => $attributes)
		{
			if(isset($attributes['possibleNames']))
			{
				foreach($attributes['possibleNames'] as $possibleName => $position)
				{
					foreach($fileHeaders as $key => $header)
					{
						if($header == $possibleName)
						{
							$fieldTypes['fields'][$type]['possibleNames'][$header] = $key;
							unset($fileHeaders[$key]);
							break;

						}
					}
					if(isset($fieldTypes['fields'][$type]['possibleNames']))
					{
						foreach($fieldTypes['fields'][$type]['possibleNames'] as $processedName => $pos)
						{
							if(is_null($fieldTypes['fields'][$type]['possibleNames'][$processedName]))
							{
								unset($fieldTypes['fields'][$type]['possibleNames'][$processedName]);
							}
						}
						if(count($fieldTypes['fields'][$type]['possibleNames'])<1)
						{
							unset($fieldTypes['fields'][$type]['possibleNames']);
						}
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
			function($params)
			{
				if(isset($params['flag2']))
				{
					if(array_key_exists($params['flag2'], $params['campaigns']))
					{
						return 'OB '.$params['tab'].' '.$params['campaigns'][$params['flag2']];
					}
				}
				return 'OB '.$params['tab'].' '.$params['camp'];
			};
			if(in_array($params['camp'], getCampaignsWithFixedWidthFormat()))
			{
				$returnArray = 
				[
						'fixedWidth' => true,
						'hasHeaders' => false,
						'fields' => 
						[
							'BILLING_TELEPHONE_NBR' =>
							[
								'position' => 85,
								'length' => 10
							],
							'OUTPUT_ID' =>
							[
								'position' => 1,
								'length' => 10
							],
							'LEAD_ID'=>
							[
								'position' => 11,
								'length' => 15
							],
							'LEAD_TYPE_CD' =>
							[
								'position' => 26,
								'length' => 2
							],
							'CMP_PLANNER_ID'=>
							[
								'position' => 28,
								'length' => 10
							],
							'CAMPAIGN_ID'=>
							[
								'position' => 38,
								'length' => 9
							],
							'EVENT_CD'=>
							[
								'position' => 47,
								'length' => 9
							],
							'LEAD_SEGMENTATION'=>
							[
								'position' => 56,
								'length' => 4
							],
							'OUTPUT_SEGMENTATION_CD'=>
							[
								'position' => 60,
								'length' => 4
							],
							'fileName' =>
							[
								'default' => $params['csvFileName']
							]
						]
				];
			}
			else 
			{
					$returnArray =
					[
						'hasHeaders' => true,
						'fields' => 
						[
							'BILLING_TELEPHONE_NBR' => 
							[
								'possibleNames' => 
								[
									'ACCT_BILLING_TELEPHONE_NBR' => null,
									'BILLING_TELEPHONE_NUMBER'=> null,
									'BILLING_TELEPHONE_NBR' => null,
									'PRIMARY_PHONE_NBR'=> null,
									'BTN' => null,
									'Billing_Contact_Name' => null
								]
							],
							'OUTPUT_ID' =>
							[
								'possibleNames' => 
								[
									'OUTPUT_ID' => null
								]
							],
							'LEAD_ID'=>
							[
								'possibleNames' => 
								[
									'LEAD_ID' => null
								]
							],
							'LEAD_TYPE_CD' =>
							[
								'possibleNames' => 
								[
									'LEAD_TYPE_CD' => null
								]
							],
							'CMP_PLANNER_ID'=>
							[
								'default' => 'LS-ALL'
							],
							'CAMPAIGN_ID'=>
							[
								'possibleNames' => 
								[
									'CAMPAIGN_ID' => null
								],
								'default' => '201202012'
							],
							'EVENT_CD'=>
							[
								'default' => 'UVRSETRAL'
							],
							'LEAD_SEGMENTATION'=>
							[
								'possibleNames' => 
								[
									'LEAD_SEGMENTATION' => null,
									'LIST_SEGMENT' => null
								]
							],
							'OUTPUT_SEGMENTATION_CD'=>
							[
								'default' => '0001'
							],
							'fileName' =>
							[
							'default' => $params['csvFileName']
							]
						]
					];
			}
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

function detectDelimiter($file)
{
	$handle = fopen($file, "r");
	$possibleDelimiters = [",", "\t", "|"];
	$myDelimiter = '`';
	foreach($possibleDelimiters as $delimiter)
	{
		$csvData1 = fgetcsv($handle, 0, $delimiter);
		$csvData2 = fgetcsv($handle, 0, $myDelimiter);
		if(count($csvData1) >= count($csvData2))
		{
			$myDelimiter = $delimiter;
		}
	}
	return $myDelimiter;
}
?>