<?php

/**
 * DBAccessorObj class
 *
 * @author  Diego Saa <diego.saa@onlineacg.com>
 */

class DBAccessorObj extends DataBaseAccessor
{
	//protected $db_connection;
	
	protected $dbTable = AppConst::TABLE_OBJECT;
	
	protected $PKey = 'object_id';
	
	protected $virtualDbTable;
	
	protected $virtualDbTableName;
	
	/**
	 *
	 * @see DataBaseAccessor::setConfig()
	 */
	protected function setConfig()
	{
		$this->setPKey(AppConst::OBJECT_ID);
		$this->setDbTable(AppConst::TABLE_OBJECT);
		$this->setDbConnectionIdentifier(AppConst::MYSQL_RW);
		//$this->setVirtualDbTableWithTableName($this->virtualDbTable);
	}
	
	/**
	 * GetInstance
	 * @param int|string $Id
	 * @param string $class
	 * @param array $params
	 * @return LeadListFormat
	 */
	public static function getInstance( $Id=null, $class = null, $params = null)
	{
		/* @var $instance DBAccessorObj */
		$instance = parent::getInstance($Id,$class, $params);
		return $instance;
	}
	
	public static function getInstanceBy(array $filters = null, $class = null)
	{
		/* @var $instance DBAccessorObj */
		$instance = parent::getInstanceBy($filters, $class);
		return $instance;
	}
	
	
	
	public static function getSQL($params)
	{
		/* @var $virtualBaseTable FieldFormat */
		$filters = [];
		$virtualBaseTable = VirtualTable::getInstanceBy($filters)->baseTable;
		$params->db_connection = $virtualBaseTable->getDBConnection();
		$params->baseTable = AppConst::TABLE_OBJECT;
		if(is_null($params->jointFields))
		{
			$params->jointFields = [];
		}
		/* @var $table Table */
		$table = VirtualTable::getInstance();
		/* @var $objectIsType RelationObjectIsType */
		$objectIsType = RelationObjectIsType::getInstance();
		$params->jointFields[] =  
		[
			[
				[
					$table->getDbTable(),
					$table->getPKey()
				],
				[
					$objectIsType->getDbTable(),
					AppConst::OBJECT_ID
				]
			]
		];
		return parent::getSQL($params);
	}
	
	/**
	 * Gets all the attribute types associated with a determined virtual table or class that inherits from DBAccessorObj
	 * @param string $className
	 */
	static public function getAttributeTypesForObject($obj)
	{
		$params = new QueryParameter();
		$params->baseTable = Table::getInstance();
		$jointFields =
		[
			[
				AppConst::TABLE_TABLE,
				Table::TABLE_ID
			],
			[
				AppConst::TABLE_ATTRIBUTE_TYPE,
				Table::TABLE_ID
			]
		];
		$params->jointFields =
		[
			[
				$jointFields
			]
		];
		$myColumn = DBColumn::getInstance();
		$myColumn->setColumnName(Table::TABLE_ID)->setTable(AppConst::TABLE_ATTRIBUTE_TYPE);
		$params->filters =
		[
			[
				$myColumn,
				$obj->getVirtualDbTable()->getId(),
				'='
			]
		];
		return DataBaseAccessor::getResults($params);
	}
	
	/**
	 * Find all existing records for passed filters
	 *
	 * @param array $filters
	 * @param string $orderby   Optional, SQL Order by string
	 * @param int $limit    MySQL Limit to # results
	 * @param int $offset   MySQL Offset for Limit
	 * @access static
	 * @return array
	 */
	static public function readListBy( $filters = null, $orderBy='', $limit=0, $offset=0, $class= null)
	{
		$orderBy = Utils_Format::prepareInput($orderBy);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$class = empty($class)?get_called_class():$class;
		//$obj =  $class::getInstance(null, $class);
		if( empty($filters) ) 
		{
			$filters = array( array('1', 1, '=') ); // get all
		}
		$obj =  $class::getInstance(null, $class);
		$attributeTypes = self::getAttributeTypesForObject($obj);
		$attributeIds = [];
		$attributesInFilters = [];
		foreach($attributeTypes as $attributeType)
		{
			if(!isset($attributeIds[$attributeType['data_type']]))
			{
				$attributeIds[$attributeType['data_type']] = [];
			}
			$attributeIds[$attributeType['data_type']][$attributeType['attribute_type_id']] = $attributeType;
			$attributeTypesInFilters[] = $attributeType;
			foreach($filters as $filter)
			{
				if($attributeType['attribute_type_name']==$filter[0])
				{
					$attributesInFilters[] = $attributeType;
				}				
			}
		}
		//query the object that has attributes according to filters
		$params = new QueryParameter();
		$params->db_connection = DBConnection::getByIdentifier(AppConst::MYSQL_RW);
		$jointFields = [];
		$additionalFields = [];
		foreach($filters as $filter)
		{
			foreach($attributeIds as $dataType => $aTypes)
			{	
				if(!isset($attribFilters[$dataType]))
				{
					$attribFilters[$dataType] = [];
				}
				foreach($aTypes as $aTypeId => $aType)
				{
					if(!isset($params->baseTable))
					{
						$baseTable = $dataType.$aTypeId;
						$params->baseTable =
						[
							QueryParameter::STATEMENT => $dataType.'_attribute',
							QueryParameter::ALIAS => $dataType.$aTypeId,
							QueryParameter::PARAM_CONSTANT => true
						];
						if(!isset($params->filters))
						{
							$params->filters =
							[
								[
									DBColumn::getInstance()->setColumnName('attribute_type_id')->setTable([$dataType.$aTypeId]),
									$aType['attribute_type_id'],
									'='
								]
							];
						}
					}
					else
					{
						if(!isset($jointFields[$dataType]))
						{
							$jointFields[$dataType] = [];
						}
						$jointFields[$dataType][]= 
						[
							[
								[
									[
										QueryParameter::STATEMENT => $dataType.'_attribute',
										QueryParameter::ALIAS => $dataType.$aTypeId,
										QueryParameter::PARAM_CONSTANT => true
									],
									'object_id'
								],
								[
									$params->baseTable,
									'object_id'
								],
							],
							[	
								[
									[
										QueryParameter::STATEMENT => $dataType.'_attribute',
										QueryParameter::ALIAS => $dataType.$aTypeId,
										QueryParameter::PARAM_CONSTANT => true
									],
									'attribute_type_id'
								],
								[
									QueryParameter::PARAM_CONSTANT,
									$aTypeId
								]
							]
						];
					}
					if(!isset($additionalFields[$dataType]))
					{
						$additionalFields[$dataType] = 
						[
							[
								'object_id',
								QueryParameter::PARAM_CONSTANT,
								$dataType.$aTypeId.'.'.'object_id'
							]
						];
					}
					$additionalFields[$dataType][] = 
					[
						$aType['attribute_type_name'], 
						QueryParameter::PARAM_CONSTANT,
						$dataType.$aTypeId.'.'.$dataType.'_attribute_value'
					];
					if($filter[0]==$aType['attribute_type_name'])
					{
						if(!isset($params->filters))
						{
							$params->filters = [];
							[
								[
									DBColumn::getInstance()->setColumnName('table_id')->setTable(AppConst::TABLE_TABLE),
									$obj->virtualDbTable->getId(),
									'='
								]
							];
						}
						$params->filters = array_merge
						(
								$params->filters,
								[
									[
										DBColumn::getInstance()->setColumnName($dataType.'_attribute_value')->setTable([$baseTable]),
										$filter[1],
										$filter[2]
									],
									[
										DBColumn::getInstance()->setColumnName('attribute_type_id')->setTable([$baseTable]),
										$aTypeId,
										'='
									]
								]
						);
					}
				}	
			}
		}
		$params->additionalFields = [];
		foreach($additionalFields as $dataType => $additionalFieldsOfType)
		{
			foreach($additionalFieldsOfType as $additionalField)
			{
				$params->additionalFields[] =  $additionalField;
			}
		}
		$params->jointFields = [];
		foreach($jointFields as $dataType => $jointFieldsOfType)
		{
			foreach($jointFieldsOfType as $jointField)
			{
				$params->jointFields[] =  $jointField;
			}
		}
		$results = [];
		//echo DataBaseAccessor::getSQL($params);
		
		foreach(DataBaseAccessor::getResults($params) as $objectAttributes)
		{
			$func = function($object) use($objectAttributes)
			{
				foreach( $objectAttributes as $newObjectAttributeName => $newObjectAttributeValue)
				{
					if($newObjectAttributeName == $object->PKey)
					{
						$object->setId($newObjectAttributeValue);
					}
					else
					{
						$object->set($newObjectAttributeName, $newObjectAttributeValue);
					}
				}
				return $object;
			};
			$newObj = clone $obj;
			$newObj->exists = true;
			$results[] = $func($newObj);
		}
		return $results;
	}
	
	/**
	 * Read single data record from database
	 * @access public
	 * @param $filters ARRAY (opt.)
	 * @return Child
	 */
	public function read(array $filters=null )
	{
		$class = get_class($this);
		foreach($class::readListBy($filters, null, null, null, null, true) as $objectInDb);
		{
			$exists = true;
			foreach($this->data as $attributeName => $attributeValue)
			{
				if($attributeValue != $objectInDb->$attributeName)
				{
					$exists = false;
					continue;
				}			
			}
			if($exists)
			{
				$return = $objectInDb;
				foreach($objectInDb as $k => $v)
				{
					$this->$k = $v;
				}
			}
			else
			{
				$return = $this;
			}
		}
		return $this;
	}
	
	public function setVirtualTableAttributes()
	{
		foreach
		(
				AttributeType::readListBy
				(
						[
							[
								DBColumn::getInstance()->setColumnName('table_id')->setTable('attribute_type'), 
								$this->getVirtualDbTable()->getId(), 
								'='
							]
						]
				) as $attributeType
		)
		{
			/* @var $attributeType AttributeType */
			$attributeTypeId = $attributeType->getId();
			$attributeTypeName = $attributeType->attribute_type_name;
			$type = ucfirst($attributeType->data_type).'Attribute';
			$attribute = $type::getInstance();
			/* @var $attribute VarcharAttribute */
			$attribute->read
			(
					[
						[
							DBColumn::getInstance()->setColumnName('object_id')->setTable($attributeType->data_type.'_attribute'), 
							$this->getId(), 
							'='
						], 
						[
							DBColumn::getInstance()->setColumnName('attribute_type_id')->setTable($attributeType->data_type.'_attribute'),
							$attributeTypeId, 
							'='
						]
					]
			);
			$this->data[$attributeTypeName] = $attribute;
		}
	}
	
	public function getVirtualTableAttributes()
	{
		return $this->attributes;
	}
	
	/**
	 * @return Table
	 */
	public function getVirtualDbTable()
	{
		if(!isset($this->virtualDbTable))
		{
			if(isset($this->virtualDbTableName))
			{
				$this->setVirtualDbTableWithTableName($this->virtualDbTableName);
			}
			else
			{
				echo "Error: the virtualDbTableName is not set for ".__CLASS__;
			}
		}
		return $this->virtualDbTable;
	}
	
	/**
	 * @return Table
	 */
	public function getVirtualDbTableName()
	{
		return $this->virtualDbTableName;
	}
	
	/**
	 * 
	 * @param Table $virtualDbTableName
	 */
	public function setVirtualDbTableWithTableName($virtualDbTableName)
	{
		$table = Table::getInstance();
		$table->read
		(
				[
					[
						DBColumn::getInstance()->setColumnName(Table::TABLE_NAME)->setTable($table),
						$virtualDbTableName,
						'='
					]
				]
		);
		$this->virtualDbTable = $table;
	}

	/**
	 * Insert new record into database.
	 * When insert was successfull it returns a instance of inserted data, when
	 * it failed, it throws UbException
	 * @access public
	 * @throws UbException
	 * @return Child
	 */
	public function insert($exclusions=null, $updateOnDuplicate = false)
	{	
		$object = Object::getInstance();
		/* @var $object Object */
		$object->table_id = $this->getVirtualDbTable()->getId();
		$object->insert();
		foreach($this->data as $key => $value)
		{
			if($key != 'object_id')
			{
				/* @var $attributeType AttributeType */
				$attributeType = AttributeType::getInstance();
				$attributeType->read
				(
						[
							[
								DBColumn::getInstance()->setTable(AppConst::TABLE_ATTRIBUTE_TYPE)->setColumnName('attribute_type_name'),
								$key,
								'='
							]
						]
				);
				if($attributeType->exists())
				{
					$attributeDataType = ucfirst($attributeType->data_type).'Attribute';
					$attribute = $attributeDataType::getInstance();
					$attribute->object_id = $object->getId();
					$attribute->attribute_type_id = $attributeType->attribute_type_id;
					$attribute->table_id = $object->table_id;
					$valueName = $attributeType->data_type.'_attribute_value';
					$attribute->$valueName = $value;
					$attribute->insert();
				}
			}
		}
		$this->read();
		return $this;
	}

// 	private function createTable()
// 	{
// 		$params = array(self::ATTRIBUTES => array('table_name' => $this->getDbTable()));
// 		/* @var $table Table */
// 		$table = Table::getInstance(null, null, $attributes);
// 		$params = new QueryParameter();
// 		$params->repositoryName = $this->getDbTable();
// 		$params->queryFieldsDescription = array();
// 		$table->setColumnsFromResults($params);
// 		$table->set('table_name', $params->repositoryName);
// 		$table->assureExistence();
// 	}

	/**
	 * Update data to database
	 * @access public
	 * @return BOOLEAN
	 */
	public function update()
	{
		if( !$this->isModified ) return true;
		if( empty($this->Id) ) return false;

		$sql = "UPDATE `".APP::$DBTABLES[$this->dbTable]."` SET ";
		$sql .= Utils::sqlPrepareSet($this->data, array($this->PKey));
		$sql .= " WHERE `".$this->PKey."` = " . (int)$this->Id;

		return MySQL::query($sql, $this->getDBConnection()->getResource());
	}

	/**
	 * Shortcut method which will determine whether a row
	 * with the current instances properties exists. If so, it will
	 * preload those values (side effects).
	 *
	 * @access public
	 * @return boolean
	 */
	public function exists($filters = null)
	{
		$this->read($filters);
		return $this->exists;
	}

	public function reConfig($pKey, $dbTable, $connection = MySQL::MYSQL_GLOBAL)
	{
		$this->setPKey($pKey);
		$this->setDbTable($dbTable);
		$this->setConnection($connection);
		$this->setId($this->getId());
	}
	
	/**
	 * Gets the objects of the specified type and that have the specified attributes.
	 * @param array $filters
	 * @param string $type
	 */
	public static function getObjectsOfTypeBy(array $filters, $type = null)
	{		
	}
	
	/**
	 * Clone method
	 * @access protected
	 */
	protected function __clone()
	{
		foreach($this as $propertyName => $propertyValue)
		{
			if(is_object($propertyValue))
			{
				$this->$propertyName = $propertyValue;
			}
		}
		$this->setId(uniqid('I', true));
		return $this;
	}
}