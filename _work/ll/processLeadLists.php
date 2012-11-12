<?php
//$fileName = 'R155022_052312_MW_NEWCUST_OUTPUT_ACG_ATT_Proprietary_Restricted_authorized_individuals_only.txt';
//$csvFileName = 'R155022_052312_MW_NEWCUST_RTN';
//set_time_limit(120);
//ini_set('memory_limit', '-1');

//require ('../../_inc/config.main.php');
set_time_limit(0);
define('DS', '\\');
$dir = 'C:'.DS.'Documents and Settings'.DS.'diego.saa'.DS.'My Documents'.DS.'leads'.DS;

/*$params = 
[
	'fileSizeLimit' => function($leadCount)
						{
							return round($leadCount/4);
						}
];*/
$params = [];
$params['segmentBy'] = 'stateOrProvince';
$params['dateReceived'] = '082412';
$totalLeads = processLeadListsInDirectory($dir, 'writeCSV', $params);
echo "wrote ".$totalLeads." leads in total";


function processLeadListsInDirectory($dir, $function = 'writeCSV', &$params)
{
	$i = 0;
	if (($dh = opendir($dir)) !== false)
	{
		while (($file = readdir($dh)) !== false)
		{
			if(!in_array($file, ['.', '..']))
			{
				if(is_dir($dir.DS.$file))
				{
					if(!in_array($file, ['processed', 'loaded', 'imported']))
					{
						$i+= processLeadListsInDirectory($dir.DS.$file, $function, $params);
					}
				}
				elseif(is_file($dir.DS.$file))
				{
					//if($file == 'SE_FD_ACG_080812.TXT')
					//if($file == 'SEBUS_WBFDOTM_NBM120814_ACG_AT&T_Proprietary_(Restricted)_authorized_individuals_only.txt')
					if($file == 'SEBUS_WBLLSLLOTM_120823_ACG_AT&T_Proprietary_(Restricted)_authorized_individuals_only.txt')
					//if($file == 'R156090_SE_IPBB_Post_Install_20120824.txt')
					//if(basename($dir) == 'NEW BUS LIC')
					{
						if(in_array(strtolower(pathinfo($dir.DS.$file)['extension']), ['txt', 'csv', 'xml', 'xlsx']))
						{
							$i+= $function($dir.DS.$file, $params);
						}						
					}
				}
			}
		}
	}
	return $i;
}

function getSegments(&$params)
{
	if($params['vendor'] == 'COX')
	{
		if(!in_array($params['camp'], ['NBA', 'OnBoarding']))
		{
			$segments = false;
		}
		else
		{
			$segments = getCoxMarkets();
		}
	}
	elseif($params['vendor'] == 'Bridgevine')
	{
		$segments = getBridgevineSegments();
	}
	else
	{
		$segments = getCampaignsForTab($params['tab']);
	}
	return $segments;
}

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

function writeCSV($fileName, &$params)
{
	//xdebug_break();
	$campPath = dirname($fileName);
	$camp = basename($campPath);
	$tab = basename(dirname($campPath));
	$region = basename(dirname(dirname($campPath)));
	$vendor = basename(dirname(dirname(dirname($campPath))));
	$path_parts = pathinfo($fileName);
	
	$dir = './'.$vendor.'/'.$region.'/'.$tab.'/'.$camp.'/';
	if(!is_dir($dir))
	{
		mkdir ($dir, 0755, true);
	}
	
	$leadsArray = file($fileName);
	
	$params['region'] = $region;
	$params['vendor'] = $vendor;
	$params['tab'] = $tab;
	$params['camp'] = $camp;
	$params['campaigns'] = getCampaignsForTab($tab);
	$params['delimiter'] = detectDelimiter($fileName);
	$params['segments'] = getSegments($params);
	$fieldFormats = getFieldFormats($params);
	
	$params['headersForImport'] = getHeadersForImport($leadsArray[0], $fieldFormats, $params);

	$leadsToImport = array();

	$params['fp'] = [];
	foreach($params['segments'] as $segmentKey => $segmentValue)
	{
		//xdebug_break();
			$csvFileName = str_replace (' ', '_', substr($path_parts['filename'], 0, 6).'_'.$params['dateReceived'].'_'.$tab.'_'.substr($path_parts['filename'], 7, 10).'_'.substr($segmentKey, 0, 3)).'.csv';
			//xdebug_break();
			$params['fp'][$segmentKey] = new stdClass();			
			$params['fp'][$segmentKey]->handle = fopen($dir.$csvFileName, 'a');
			$params['fp'][$segmentKey]->fileName = $csvFileName;
			$params['fp'][$segmentKey]->leadCount = 0;
			$params['fp'][$segmentKey]->part = 0;
	}	
	$i = 0;	
	foreach($leadsArray as $keyLead => $lead)
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
			if($params['headersForImport']['fixedWidth'])
			{
				$params['fixedWidth'] = true;
				$leadToImport = getLeadToImport($params);
			}
		}
		else
		{
			$params['leadArray'] = explode($params['delimiter'], $lead);
			$params['leadArray'] = array_map('trim', $params['leadArray']);
		}
		$leadToImport = getLeadToImport($params);
		if(isset($params['fileSizeLimit']))
		{
			xdebug_break();
			if($params['fp'][$params['segment']]->leadCount >= $params['fileSizeLimit'](count($leadsArray)))
			{
				$params['fp'][$segmentKey]->part++;
				$csvFileName = str_replace (' ', '_', substr($path_parts['filename'], 0, 6).'_'.$params['dateReceived'].'_'.$tab.'_'.substr($path_parts['filename'], 7, 10).'_'.substr($segmentKey, 0, 2)).$params['fp'][$segmentKey]->part;
				$params['fp'][$segmentKey]->handle = fopen($dir.$csvFileName.'.csv', 'a');
				$params['fp'][$segmentKey]->fileName = $csvFileName;
				$params['fp'][$segmentKey]->leadCount = 0;
				
			}
		}
		xdebug_break();
		if(isset($params['fp'][$params['segment']]->handle))
		{
			fputcsv($params['fp'][$params['segment']]->handle, $leadToImport);
			$params['fp'][$params['segment']]->leadCount++;
			xdebug_break();
			$i++;
		}
		if(isset($params['function']))
		{
			unset($params['function']);
		}
	}
	
	foreach($params['fp'] as $linkKey => $link)
	{
		fclose($link->handle);
		echo "wrote ".$link->leadCount." leads to ".$link->fileName."</p>";
	}
	return $i;
}

function getLeadSegment(&$params)
{ 
	xdebug_break();
	if($params['segments'])
	{
		foreach($params['segments'] as $segmentKey => $segment)
		{
			xdebug_break();
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
	return $segmentKey;
}

function getLeadToImport(&$params)
{
	//xdebug_break();
	$leadToImport = array();
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
		$field = getFieldToImport($key, $params);
		if($key  == 'stateOrProvince')
		{
			$params['state'] = $field;
		}
		$leadToImport[] = $field;
	}
	return $leadToImport;
}

function getFieldToImport($key, &$params)
{		
	if(isset($params['headersForImport']['fields'][$key]['function']))
	{
		if($params['headersForImport']['fields'][$key]['function'])
		{
			$params['function'] = $params['headersForImport']['fields'][$key]['function'];
		}
	}
	unset($params['function']);
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
				$field = getFieldByDefault($params['headersForImport'], $key, $params);
			}
			else
			{
				$field = getFieldByPosition($key, $params['headersForImport']['fields'][$key]['position'], $params);
			}
		}
		else
		{
			xdebug_break();
			$field = getFieldByDefault($key, $params);
		}
	}
	if($params['segmentBy'] == $key)
	{
		//xdebug_break();
		$sillyFunction = function() use ($key, $params, $field)
							{
								foreach($params['segments'] as $segmentKey => $values)
								{
									if(in_array($field, $values))
									{
										return $segmentKey;
									}
								}
							};
		$params['segment'] = $sillyFunction();
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
					$field = substr($params['leadArray'], $position, $params['length']);
				}
			}
			else
			{
				$field = (string)$params['leadArray'][$position];
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
		//xdebug_break();
		return substr($params['leadArray'][$params['position']], 0, 10);
	};
	$zipFunction = function($params)
	{
		return substr($params['leadArray'][$params['position']], 0, 5);
	};
	$concatenate =
	function(&$params, $word)
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
			$reqCampaignName =
			function(&$params)
			{
				if(isset($params['segment']))
				{
					if(array_key_exists($params['segment'], $params['campaigns']))
					{
						xdebug_break();
						return 'OB '.$params['tab'].' '.$params['campaigns'][$params['segment']];
					}
				}
				return 'OB '.$params['tab'].' '.$params['camp'];
			};
			if(!in_array($params['camp'], ['ALW 2DAY FD', 'NBM ACQ ALW 2DAY FD', 'WINBACK']))
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
								'default' => $params['csvFileName']
							],
							'flag2'  =>
							[
							],
							'flag3' =>
							[
								'function' => function(&$params)
												{
													if(isset($params['segment']))
													{
														if(array_key_exists($params['segment'], $params['campaigns']))
														{
															xdebug_break();
															return 'OB '.$params['tab'].' '.$params['campaigns'][$params['segment']];
														}
													}
													return 'OB '.$params['tab'].' '.$params['camp'];
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
								]
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
								'function' => $phoneFunction
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
								]
							],
							'city' =>
							[
								'possibleNames' =>
								[
									'ACCT_BILL_ADDR_CITY_NM'=> null,
									'BILLED_CITY' => null,
									'CITY'=> null,
									'City' => null
								]
							],
							'stateOrProvince'  =>
							[
								'possibleNames' =>
								[
									'ACCT_BILL_ADDR_STATE_CD'=> null,
									'BILLED_STATE_CD' => null,
									'STATE'=> null,
									'State' => null
								]
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
								'function' => $zipFunction
							],
							'flag1'  =>
							[
								'function' => function(&$params){return $params['csvFileName'];}
							],
							'flag2'  =>
							[
								'possibleNames' =>
								[
									'LIST_SEGMENT_OTM'=> null,
									'LIST_SEGMENT_DM'=> null,
									//'LEAD_SEGMENTATION_CD' => null,
									'LIST_SEGMENT' => null,
									'SEGMENT'=> null
								],
								'default' => ''
							],
							'flag3' =>
							[
								'function' => $reqCampaignName
			//					'default' => $reqCampaignName($segmentName, $campaignArray)
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
			switch($params['camp'])
			{
				case 'NBA':
					xdebug_break();
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
		case 'CAM':
			return
			[
				'OnBoarding' => 'OnBoarding',
				'NBA' => 'NBA'
			];
		default:
			return 
			[
				'NA' => 'NA'		
			];
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
		fseek($handle, 0);
		$csvData1 = fgetcsv($handle, 0, $delimiter);
		fseek($handle, 0);
		$csvData2 = fgetcsv($handle, 0, $myDelimiter);
		if(count($csvData1) >= count($csvData2))
		{
			$myDelimiter = $delimiter;
		}
	}
	return $myDelimiter;
}
?>