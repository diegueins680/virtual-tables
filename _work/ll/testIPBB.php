<?php
function getColumnForRequirementAttribute($type, $headers)
{
	switch($type)
	{
		case 'dateReceived':
			$column = getKeyForValue($headers, 'Date Leads Sent');
			break;
		case 'camp':
			$column = getKeyForValue($headers, 'Campaign Name');
			break;
		case 'region':
			$column = getKeyForValue($headers, 'Region');
			break;
		case 'vendor':
			$column = getKeyForValue($headers, 'Vendor ID');
			break;
		case 'tab':
			$column = getKeyForValue($headers, 'Campaign Program');
			break;
		case 'inputFile':
			$column = getKeyForValue($headers, 'Lead List File Name and/or FTP Site Address');
			break;
		case 'segment':
			$column = getKeyForValue($headers, 'LIST/OP SEGMENT #');
		break;
		case 'layout':
			$column = getKeyForValue($headers, 'FILE LAYOUT');
		break;
		case 'segmentBy':
			$column = getKeyForValue($headers, 'Segment by');
		break;
		
	}
	return $column;
}

function getValueOfRequirementField($type, &$params)
{
	$column = getColumnForRequirementAttribute($type, $params['requirements'][1]);
	if(!isset($params['requirements'][$params['requirementIndex']][$column]))
	{
		var_dump(debug_backtrace(true));
		var_dump($params['requirements']);
		var_dump($params['requirementIndex']);
		var_dump($column);
		die;
	}
	$value = $params['requirements'][$params['requirementIndex']][$column];
	switch($type)
	{
		case 'vendor':
			$value = substr($value, 0, 3);
			break;
		case 'segmentBy':
			$value = explode( ',', $value );
			break;
		default:
			break;
	}
	return $value;
}

function getConcatenatedSegment(&$params)
{
	xdebug_break();
	$concatenatedSegment = '';
	if(isset($params['leadArray']))
	{
		foreach($params['segmentBy'] as $segmentBy)
		{
			$segment = getFieldToImport($segmentBy, $params).'_';
			//fix for segments that start with 0 in the input file, but appear wihout it in the requirements
			$requirement = getReqForSegment($params, $segment);
			$concatenatedSegment.= getValueOfRequirementField('segment', $params); 
		}
		$concatenatedSegment = trim($concatenatedSegment, ',');
	}
	else
	{
		//xdebug_break();
		$concatenatedSegment.= getValueOfRequirementField('segment', $params);
	}
	$concatenatedSegment = str_replace(' ', '_', $concatenatedSegment);
	return $concatenatedSegment;
}

function getFpKey(&$params)
{
	//xdebug_break();
	return $params['inputFile'].'_'.getConcatenatedSegment($params);
}

function getFileHandler(&$params)
{
	$fKey = getFpKey($params);
	if(!isset($params['fp'][$fKey]))
	{
		$params['fp'][$fKey] = new stdClass();
		$dir = '';
		$reqKey = getReqKeyForFileName($params);
		$dirParts = ['vendor', 'region', 'tab', 'camp'];
		foreach($dirParts as $dirPart)
		{
			$dir .= getValueOfRequirementField($dirPart, $params).'/';
		}
		$params['fp'][$fKey]->dir = $dir;
		if(!is_dir($params['fp'][$fKey]->dir))
		{
			if (!file_exists($params['fp'][$fKey]->dir))
			{
				mkdir ($params['fp'][$fKey]->dir, 0755, true);
			}
		}
		$nameParts = ['dateReceived', 'vendor', 'region', 'tab', 'camp', 'inputFile','segment'];
		$csvFileName = getCsvFileName($params, $nameParts);
		$params['fp'][$fKey]->handle = fopen($params['fp'][$fKey]->dir.$csvFileName, 'a');
		$params['fp'][$fKey]->fileName = $csvFileName;
		$params['fp'][$fKey]->leadCount = 0;
		$infos = ['dateReceived', 'vendor', 'region', 'tab', 'camp', 'inputFile','segment'];
		foreach($infos as $info)
		{
			$params['fp'][$fKey]->$info = getValueOfRequirementField($info, $params);
		}
	}
	return $params['fp'][$fKey];
}

function setFileHandlers(&$params)
{
	$params['fp'] = [];
	$params['files'] = [];
	foreach($params['requirements'] as $key => $requirement)
	{
		if($key > 1)
		{
			$params['requirementIndex'] = $key;
			$params['segmentBy'] = getValueOfRequirementField('segmentBy', $params);
			$params['inputFile'] = getValueOfRequirementField('inputFile', $params);
			getFileHandler($params);
		}
	}
	return $params['fp'];
}

function getCsvFileName(&$params, $parts)
{
	$csvFileName = '';
	foreach($parts as $namePart)
	{
		$value = getValueOfRequirementField
		(
				$namePart,
				$params
		);
		switch($namePart)
		{
			case 'dateReceived':
				/* @var $dateReceived DateTime */
				$dateReceived = date_create_from_format('j-M', $value);
				$value = $dateReceived->format('md');
				break;
			case 'inputFile':
				if(!in_array($value, $params['files']))
				{
					$params['files'][] = trim(trim($value, '.pgp'),'.pgp');
				}
				$value = substr(explode('.', $value)[0], 0, 10);
				break;
		}
		$value = str_replace(' ', '_', $value);
		$csvFileName.= $value;
		$csvFileName .= '_';
	}
	$csvFileName = trim($csvFileName, '_');
	$csvFileName = $csvFileName.'.csv';
	return $csvFileName;
}

function getKeyForValue($headers, $needle)
{
	foreach($headers as $key => $header)
	{
		if ( $header === $needle )
			return $key;
	}
	return false;
}

$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);

function getReqArray($fileName = false)
{
	$inputFileName = 'reqs.xls';
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	$requirements = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	return $requirements;
}

$params['requirements'] = getReqArray(true);
//var_dump($params['requirements']);
//die;
//require('myArray.php');
//$params['requirements'] = $myArray;
function getReqForSegment(&$params, $segment)
{
	xdebug_break();
	foreach($params['requirements'] as $key => $req)
	{
		if($req[getColumnForRequirementAttribute('segment', $params['requirements'][1])] == $params[$segment])
		{
			$params['requirementIndex'] = $key;
			return $params['requirements'][$key];
		}
	}
}

function getReqKeyForFileName(&$params, $fileName = null)
{
	if(empty($fileName))
	{
		$fileName = $params['inputFile'];
	}
	foreach($params['requirements'] as $key => $req)
	{
		if
		(
				(
					explode
					(
							'.',
							$req
							[	
								getColumnForRequirementAttribute
								(
										'inputFile', 
										$params
										[
											'requirements'
										]
										[1]
								)
							]
					)[0]		
					== 
					explode('.', $fileName)[0]
				)
		)
		{
			return $key;
		}
	}
}
/*
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
/*
$params['inputFile'] = '157415_SepACG_SE_AT&T_Proprietary_(Restricted)_authorized_individuals_Only.txt';
$params['inputFile'] = 'files/'.$params['inputFile'];
$params['dateReceived'] = '29-Aug';
$params['camp'] = '3PLUS LNS RWRD CRD LCL MKT';
$params['region'] = 'SE';
$params['vendor'] = 'ACG';
$params['tab'] = 'RTN';

*/
//$params['segmentBy'] = ['flag2'];
$params['fp'] = setFileHandlers($params);



function setFileHandler($requirement)
{
	$fpArray = [];
	return $fpArray;
}

$params['camp'] = function() use (&$params)
{
	$req = getReqForSegment($params);
	$campaign = getValueOfRequirementField('camp', $params);
	return $campaign;
};

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
//$file = 'files/SE_FD_ACG_082912.TXT';
writeCSV($params);

function getBridgevineSegments()
{
	return
	[
	'IL' => ['IL'],
	'MD' => ['MD']
	];
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

function writeCSV(&$params)
{
	foreach($params['files'] as $inputFile)
	{
		$params['inputFile'] = $inputFile;
		$params['delimiter'] = detectDelimiter($inputFile);
		$params['leadsArray'] = file('files/'.$inputFile);
		$params['requirementIndex'] = getReqKeyForFileName($params, $inputFile);
		if(empty($params['requirementIndex']))
		{
			var_dump($params);
		}
		$params['vendor'] = getValueOfRequirementField('vendor', $params);
		$params['layout'] = getValueOfRequirementField('layout', $params);
		$params['region'] = getValueOfRequirementField('region', $params);
		$params['segmentBy'] = getValueOfRequirementField('segmentBy', $params);
		//xdebug_break();
		$fieldFormats = getFieldFormats($params);

		//xdebug_break();
		$params['headersForImport'] = getHeadersForImport($params['leadsArray'][0], $fieldFormats, $params);

		$leadsToImport = array();


		$i = 0;
		foreach($params['leadsArray'] as $keyLead => $lead)
		{
			if($keyLead == 0)
			{
				if($params['headersForImport']['hasHeaders'])
				{
					continue;
				}
			}
			if(isset($params['headersForImport']['fixedWidth']))
			{
				$params['leadArray'] = $lead;
				if($params['headersForImport']['fixedWidth'])
				{
					$params['fixedWidth'] = true;
				}
			}
			else
			{
				$params['leadArray'] = explode($params['delimiter'], $lead);
				$params['leadArray'] = array_map('trim', $params['leadArray']);
			}
			xdebug_break();
			$params['leadToImport'] = getLeadToImport($params);
			$handle = getFileHandler($params);
			fputcsv($handle->handle, $params['leadToImport']);
			$handle->leadCount++;
			$i++;
			if(isset($params['function']))
			{
				unset($params['function']);
			}
		}
	}
	var_dump($params['fp']);
	foreach($params['fp'] as $linkKey => $link)
	{
		fclose($link->handle);
		echo "wrote ".$link->leadCount." leads to ".$link->fileName."</p>";
	}
	return $i;
}

function getLeadSegment(&$params)
{
	if($params['segments'])
	{
		foreach($params['segments'] as $segmentKey => $segment)
		{
			if($params['segment'] == $segmentKey)
			{
				break;
			}

		}
	}
	else
	{
		$segmentKey = '';
	}
	var_dump($segmentKey);
	return $segmentKey;
}

function getLeadToImport(&$params)
{
	$params['leadToImport'] = [];
	foreach($params['headersForImport']['fields'] as $key => $importHeader)
	{
		if(isset($params['fixedWidth']))
		{
			if($params['fixedWidth'])
			{
				if(isset($params['headersForImport']['fields'][$key]['length']))
				{
					$params['length'] = $params['headersForImport']['fields'][$key]['length'];
				}
			}
		}
		xdebug_break();
		$outputPosition = $params['headersForImport']['fields'][$key]['outputPosition'];
		if(isset($params['leadToImport'][$outputPosition]))
		{
			$field = $params['leadToImport'][$outputPosition];
		}
		else
		{
			$field = getFieldToImport($key, $params);
		}
		if($key  == 'stateOrProvince')
		{
			$params['state'] = $field;
		}
		$params['leadToImport'][$outputPosition] = $field;
	}
	return $params['leadToImport'];
}

function getFieldToImport($key, &$params)
{	
	if(isset($params['headersForImport']['fields'][$key]))
	{
		if(isset($params['headersForImport']['fields'][$key]['function']))
		{
			$params['function'] = $params['headersForImport']['fields'][$key]['function'];
		}
		else
		{
			if(isset($params['function']))
			{
				unset($params['function']);
			}
		}
	}
	else
	{
		if(isset($params['function']))
		{
			unset($params['function']);
		}
	}
	if(isset($params['headersForImport']['fields'][$key]['possibleNames']))
	{
		if(count($params['headersForImport']['fields'][$key]['possibleNames'])>0)
		{
			foreach($params['headersForImport']['fields'][$key]['possibleNames'] as $header => $position)
			{
				if(!is_null($position))
				{
					break;
				}
			}
			$field = getFieldByPosition($key, $position, $params);
		}
	}
	else
	{
		if(isset($params['headersForImport']['fields'][$key]['position']))
		{
			if(is_null($params['headersForImport']['fields'][$key]['position']))
			{
				$field = getFieldByDefault($key, $params);
			}
			else
			{
				$field = getFieldByPosition($key, $params['headersForImport']['fields'][$key]['position'], $params);
			}
		}
		else
		{
			//xdebug_break();
			$field = getFieldByDefault($key, $params);
		}
	}
	if(in_array($key, $params['segmentBy']))
	{
		if(!isset($params['segment']))
		{
			$params['segment'] = '';
		}
		$params['segment'] .= $field;
	}
	if(isset($params['headersForImport']['fields'][$key]['sanitize']))
	{
		$field = preg_replace("/[^A-Za-z0-9 ]/", '', $field);
	}
	return $field;
}

function getFieldByPosition($key, $position, &$params)
{
	//xdebug_break();
	if(isset($params['function']))
	{
		$newParams = ['position'];
		foreach($newParams as $param)
		{
			$params[$param] = $$param;
		}
		//xdebug_break();
		if(!$field = $params['function']())
		{
			//xdebug_break();
			$field = '';
		}

		foreach($newParams as $param)
		{
			unset($params[$param]);
		}
		unset($params['function']);
	}
	else
	{
		if(isset($params['leadArray'][$position]))
		{
			if(isset($params['fixedWidth']))
			{
				if($params['fixedWidth'])
				{
					$field = trim(substr($params['leadArray'], $position, $params['length']));
				}
			}
			else
			{
				$field = trim((string)$params['leadArray'][$position]);
			}
		}
		else
		{
			$field = '';
		}
	}
	return $field;
}

function getFieldByDefault($key, &$params)
{
	if(isset($params['headersForImport']['fields'][$key]))
	{
		$importHeader = $params['headersForImport']['fields'][$key];
	}
	
	if(isset($importHeader['function']))
	{
		//xdebug_break();
		$field = $importHeader['function']();
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

function getIndexArray($fileHeaders, &$params)
{
	$indexArray = array();
	foreach($fileHeaders as $index => $header)
	{
		foreach($params['headersForImport'] as $key => $importHeader)
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

function getHeadersForImport($fileHeadersLine, $fieldTypes, &$params)
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
	$getFilename =
	function() use (&$params)
	{
		return getFileHandler($params)->fileName;
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
	$phoneFunction = function() use (&$params)
	{
		return substr($params['leadArray'][$params['position']], 0, 10);
	};
	$zipFunction = function() use (&$params)
	{
		//xdebug_break();
		return substr($params['leadArray'][$params['position']], 0, 5);
	};
	$concatenate =
	function($word) use (&$params)
	{
		return $word.': '.$params['leadArray'][$params['position']];
	};

	$concatenate2 =
	function($headerNames, $lead, $headers)
	{
		$concatenated = '';
		foreach($headerNames as $headerName)
		{
			if($headerName == ' ')
			{
				$concatenated.= ' ';
			}
			else
			{
				$concatenated.= $lead[$headers['possibleNames'][$headerName]];
			}
		}
		return $concatenated;
	};

	switch($params['vendor'])
	{
		case 'ASP':
		case 'ACG':
		case 'ACL':
		case 'XAC':
			$returnArray = [];
			$getFileName = function() use (&$params)
			{
				$params['segment']['flag2'] = getFieldToImport('segment', $params);
				return $params['fp'][$params['inputFile'].getConcatenatedSegment($params)]->fileName;
			};
			$reqCampaignName = function() use (&$params)
			{
				return 'OB '.getValueOfRequirementField('tab', $params).' '.getFileHandler($params)->camp;
			};
			if($params['layout'] == 'AL0012')
			{
				$returnArray =
				[
					'fixedWidth' => true,
					'hasHeaders' => false,
					'fields' =>
					[
						'clientName' =>
						[
							'position' => 99,
							'length' => 200
						],
						'phoneNumber' =>
						[
							'position' => 85,
							'length' => 10
						],
						'street' =>
						[
							'position' => 451,
							'length' => 50
						],
						'city' =>
						[
							'position' => 501,
							'length' => 10
						],
						'stateOrProvince' =>
						[
							'position' => 540,
							'length' => 2
						],
						'zip' =>
						[
							'position' => 531,
							'length' => 5
						],
						'flag1'  =>
						[
							'function' => function() use (&$params)
							{
								return getFileHandler($params)->fileName;
							}
						],
						'flag2'  =>
						[
							'position' => 570,
							'length' => 2
						],
						'flag3' =>
						[
							'function' => function() use (&$params)
							{
								var_dump();
								return 'OB '.$params['fp'][getFpKey($params)]->tab.' '.$params['fp'][getFpKey($params)]->camp;
							}
						],
						'flag4'  =>
				[
				'default' => $params['region']
				],
				'flag5'  =>
				[
				'default' => $params['vendor']
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
						'clientName' =>
						[
					//		'value' => $fieldValue,
							'possibleNames' =>
							[
								'ACCT_BILL_ADDR_BUSINESS_NM'=> null,
								'BILLED_ADDRESS_BUS_NAME' => null,
								'CONTACT_NM' => null, //the key is the name, and the value is the position
								'CONTACT_NAME' => null,
								'Billing_Contact_Name' => null
							],
							'outputPosition' => 0
						],
						'phoneNumber'  =>
						[
							'possibleNames' =>
							[
								'ACCT_BILLING_TELEPHONE_NBR' => null,
								'BILLING_TELEPHONE_NUMBER'=> null,
								'BILLING_TELEPHONE_NBR' => null,
								'PRIMARY_PHONE_NBR'=> null,
								'BTN' => null
							],
							'function' => $phoneFunction,
							'outputPosition' => 1
						],
						'street' =>
						[
							'possibleNames' =>
							[
								'ACCT_BILL_ADDR_PRIMARY_TXT'=> null,
								'ACCT_BILL_ADDR_NM' => null,
								'BILLED_ADDRESS_PRIMARY' => null,
								'ADDRESS'=> null,
								'Addr' => null
							],
							'outputPosition' => 2
						],
						'city' =>
						[
							'possibleNames' =>
							[
								'ACCT_BILL_ADDR_CITY_NM'=> null,
								'BILLED_CITY' => null,
								'CITY'=> null,
								'City' => null
							],
							'outputPosition' => 3
						],
						'stateOrProvince'  =>
						[
							'possibleNames' =>
							[
								'ACCT_BILL_ADDR_STATE_CD'=> null,
								'BILLED_STATE_CD' => null,
								'STATE'=> null,
								'State' => null
							],
							'outputPosition' => 4
						],
						'zip'  =>
						[
							'possibleNames' =>
							[
								'ACCT_BILL_ADDR_ZIP_CD'=> null,
								'BILLED_ZIP_CODE' => null,
								'ZIP'=> null,
								'Zip' => null
							],
							'function' => $zipFunction,
							'outputPosition' => 5
						],
						'flag1'  => 
						[
							'function'=> $getFilename,
							'outputPosition' => 6
						],
						'flag2'  =>
						[
							'possibleNames' =>
							[
								'LIST_SEGMENT_OTM'=> null,
								'LIST_SEGMENT_DM'=> null,
						//'LEAD_SEGMENTATION_CD' => null,
								'LIST_SEGMENT' => null,
								'SEGMENT'=> null,
								'segment' => null
							],
							'default' => '',
							'function' => function() use (&$params)
							{
						//xdebug_break();
								$keysAndPositionsArray = $params['headersForImport']['fields']['flag2']['possibleNames'];
								$params['segment'] = $params['leadArray'][reset($keysAndPositionsArray)];
								return $params['segment'];
							},
							'outputPosition' => 7,
						],
						'flag3' =>
						[
							'function' => $reqCampaignName,
							'outputPosition' => 8
				//					'default' => $reqCampaignName($segmentName, $campaignArray)
						],
						'flag4'  =>
						[
							'default' => $params['region'],
							'outputPosition' => 9
						],
						'flag5'  =>
						[
							'default' => $params['vendor'],
							'outputPosition' => 10
						]
					]
				];
			}
			break;
		case 'Bridgevine':
			$returnArray =
			[
			'hasHeaders' => true,
			'fields' =>
			[
			'clientName' =>
			[
			'possibleNames' =>
			[
			'First Name' => null,
			'Last Name' => null
			],
			//	'value' => $fieldValue,
			'function' => 	function(&$params) use($concatenate2)
			{
				return $concatenate2(['First Name', ' ', 'Last Name'], $params['leadArray'], $params['headersForImport']['fields']['clientName']);
			}
			],
			'phoneNumber'  =>
			[
			'possibleNames' =>
			[
			'Phone No' => null,
			],
			'function' =>
			$phoneFunction
			],
			'street' =>
			[
			'possibleNames' =>
			[
			'New Street' => null
			]
			],
			'city' =>
			[
			'possibleNames' =>
			[
			'New City'=> null
			]
			],
			'stateOrProvince'  =>
			[
			'possibleNames' =>
			[
			'New State'=> null
			]
			],
			'zip'  =>
			[
			'possibleNames' =>
			[
			'New Zipcode' => null
			],
			'function' =>
			$zipFunction
			],
			'flag1'  =>
			[
			'possibleNames' => [],
			'default' => ''
			],
			'flag2'  =>
			[
			'possibleNames' => [],
			'default' => ''
			],
			'flag3' =>
			[
			'possibleNames' => [],
			'default' => ''
			//		'default' => $reqCampaignName($segmentName, $campaignArray)
			],
			'flag4'  =>
			[
			'possibleNames' => [],
			'default' => ''
			],
			'flag5'  =>
			[
			'possibleNames' => [],
			'default' => ''
			]
			]
			];
			break;
		case 'COX':
			switch($params['camp']())
			{
				case 'NBA':
					$returnArray =
					[
					'hasHeaders' => true,
					'fields' =>
					[
					'clientName' =>
					[
					//		'value' => $fieldValue,
					'possibleNames' =>
					[

					'BUSINESS_NAME'=> null
					]
					],
					'phoneNumber'  =>
					[
					'possibleNames' =>
					[
					'SERVICE_PHONE_NBR' => null,
					'TELEPHONE' => null
					],
					'function' =>
					$phoneFunction
					],
					'street' =>
					[
					'possibleNames' =>
					[
					'ADDRESS_LINE1',
					'PHYSICAL_STREET_ADDRESS_LINE1'=> null
					]
					],
					'city' =>
					[
					'possibleNames' =>
					[
					'ADDRESS_CITY',
					'PHYSICAL_CITY'=> null
					]
					],
					'stateOrProvince'  =>
					[
					'possibleNames' =>
					[
					'ADDRESS_STATE',
					'PHYSICAL_STATE_ABBREVIATION'=> null
					]
					],
					'zip'  =>
					[
					'possibleNames' =>
					[
					'ADDR_ZIP_5',
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
					function(&$params) use($concatenate)
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
					function(&$params)use($concatenate)
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
					function(&$params)use($concatenate)
					{
						return $concatenate($params, 'MKT');
					}
					//			'default' => $reqCampaignName($segmentName, $campaignArray)
					],
					'flag4'  =>
					[
					'possibleNames' =>
					[
					'SIC2Description' => null
					],
					'function' =>
					function(&$params)use($concatenate)
					{
						return $concatenate($params, 'SIC');
					}
					],
					'flag5'  =>
					[
					'possibleNames' => [],
					'default' => ''
					]
					]
					];
					break;
				case 'OnBoarding':
					//xdebug_break();
					$returnArray =
					[
					'hasHeaders' => true,
					'fields' =>
					[
					'clientName' =>
					[
					//		'value' => $fieldValue,
					'possibleNames' =>
					[
					'BUSINESS_NAME'=> null
					]
					],
					'phoneNumber'  =>
					[
					'possibleNames' =>
					[
					'SERVICE_PHONE_NBR' => null,
					],
					'function' =>
					$phoneFunction
					],
					'street' =>
					[
					'possibleNames' =>
					[
					'ADDRESS_LINE1'=> null
					]
					],
					'city' =>
					[
					'possibleNames' =>
					[
					'ADDRESS_CITY' => null,
					]
					],
					'stateOrProvince'  =>
					[
					'possibleNames' =>
					[
					'ADDRESS_STATE' => null,
					]
					],
					'zip'  =>
					[
					'possibleNames' =>
					[
					'ADDR_ZIP_5' => null
					],
					'function' =>
					$zipFunction
					]
					]
					];
					break;
				default:
					$returnArray =
					[
					'hasHeaders' => false,
					'fields' => [
					'clientName' =>
					[
					'default' => ''
					],
					'phoneNumber' =>
					[
					'position' => 0,
					'sanitize' => true
					],
					'street' =>
					[
					'default' => ''
					],
					'city' =>
					[
					'default' => ''
					],
					'stateOrProvince' =>
					[
					'default' => ''
					],
					'zip' =>
					[
					'default' => ''
					],
					'flag1' =>
					[
					'position' => 1
					],
					'flag2' =>
					[
					'position' => 2
					]
					]
					];
					break;
			}
			break;
			}
	return $returnArray;
}

function detectDelimiter($file)
{
	$handle = fopen('files/'.$file, "r");
	$possibleDelimiters = [",", "\t", "|"];
	$myDelimiter = '`';
	foreach($possibleDelimiters as $delimiter)
	{
		fseek($handle, 0);
		$csvData1 = fgetcsv($handle, 0, $delimiter);
		fseek($handle, 0);
		$csvData2 = fgetcsv($handle, 0, $myDelimiter);
		if(count($csvData1) >= count($csvData2))
		{
			$myDelimiter = $delimiter;
		}
	}
	fclose($handle);
	return $myDelimiter;
}
?>
?>