<?php

/**
 * DataBaseAccesor class
 *
 * @author  Diego Saa <diego.saa@onlineacg.com>
 */

abstract class DataBaseAccessor extends ObjectFactory
{
	/**
	 * Name of the variable for the database connection to be used
	 * @var string
	 */

	const FUNCTION_AVG = 'AVG';
	const FUNCTION_BIT_AND = 'BIT_AND';
	const FUNCTION_BIT_OR = ' BIT_OR';
	const FUNCTION_BIT_XOR = 'BIT_XOR';
	const FUNCTION_COUNT_DISTINCT = 'COUNT DISTINCT';
	const FUNCTION_COUNT = 'COUNT';
	const FUNCTION_GROUP_CONCAT = 'GROUP_CONCAT';
	const FUNCTION_MAX = 'MAX';
	const FUNCTION_MIN = 'MIN';
	const FUNCTION_STD = 'STD';
	const FUNCTION_STDDEV_POP = 'STDDEV_POP';
	const FUNCTION_STDDEV_SAMP = 'STDDEV_SAMP';
	const FUNCTION_STDDEV = 'STDDEV';
	const FUNCTION_SUM = 'SUM';
	const FUNCTION_VAR_POP = 'VAR_POP';
	const FUNCTION_VAR_SAMP = 'VAR_SAMP';
	const FUNCTION_VARIANCE = 'VARIANCE';

	const FUNCTION_OR = 'OR';
	const FUNCTION_AND = 'AND';

	/**
	 * Name of the primary key field
	 *
	 * @var STRING
	 **/
	protected $PKey;

	/**
	 * Counter for found rows in readListBy()
	 * @staticvar int
	 */
	public static $foundRows = array(); // arrayClassTypes('className' => $foundRows);

	/**
	 * Name of the database table
	 * @var STRING
	 */
	protected $dbTable;

	/**
	 * data modified since reading?
	 * @var BOOLEAN
	 */
	protected $isModified = false;

	/**
	 * True if there is a row in the database with the properties in it's data array
	 * @var BOOLEAN
	 */
	protected $exists = false;

	/**
	 * Storing data
	 *
	 * @var ARRAY
	 */
	protected $data = array();
	
	private $db_connection;
	
	private $dbConnectionIdentifier;
	

	/**
	 * Constructor
	 *
	 * @access protected
	 * @param int $Id
	 *
	 */
	final protected function __construct( $Id=null)
	{
		$this->setConfig();
		parent::__construct($Id);
	}
	
	/**
	 * Objects that extend this class can set their table name, db connection and primary key name in this method
	 */
	abstract protected function setConfig();

	/**
	 * Setter for PKey
	 *
	 * @access public
	 * @param String $dbTable
	 */
	public function setPKey($pKey)
	{
		$this->PKey = $pKey;
		return $this;
	}

	/**
	 * Getter for PKey
	 *
	 * @access public
	 * @param String $dbTable
	 */
	public function getPKey()
	{
		return $this->PKey;
	}

	/**
	 * Setter for dbTable
	 *
	 * @access public
	 * @param string $dbTable
	 *
	 */
	public function setDbTable($dbTable)
	{
		$this->dbTable = $dbTable;
		return $this;
	}

	/**
	 * Getter for dbTable
	 *
	 * @access public
	 * @return string
	 */
	public function getDbTable()
	{
		return $this->dbTable;
	}
	
	/**
	 * Getter for data
	 *
	 * @access public
	 * @return string
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Setter for dbConnectionIdentifier
	 *
	 * @access public
	 * @param string $identifier
	 *
	 */
	public function setDbConnectionIdentifier($identifier)
	{
		$this->dbConnectionIdentifier = $identifier;
		return $this;
	}
	
	/**
	 * Getter for dbConnectionIdentifier
	 *
	 * @access public
	 * @return string
	 */
	public function getDbConnectionIdentifier()
	{
		return $this->dbConnectionIdentifier;
	}

	/**
	 * Getter for the object's MySQL resource
	 *
	 * @access public
	 * @return DBConnection
	 */
	public function getDBConnection()
	{
		if(isset($this->db_connection))
		{
			$return = $this->db_connection;
		}
		else
		{
			$this->setConnection($this->dbConnectionIdentifier);
		}
		return $this->db_connection;
	}

	/**
	 * Calls paretn setId and sets the id property in the data_array
	 *
	 * @access public
	 * @param MIXED $id
	 */
	public function setId($id)
	{
		parent::setId($id);
		if($this->PKey != ConcreteDBAccessor::IGNORE_PKEY)
		{
			$this->data[$this->PKey] = $id;
		}
		return $this;
	}

	/**
	 * override magic getter
	 * @access public
	 * @param STRING $name
	 * @return MIXED
	 */
	public function __get($name)
	{
		if( array_key_exists($name, $this->data) )
		{
			return $this->data[$name];
			break;
		}
		elseif ($name == 'data') // get the whole data array
		{
			return $this->data;
		}
		return null;
	}

	/**
	 * @access public
	 * @param string $varName
	 * @return bool
	 */
	public function __isset( $varName )
	{
		return (parent::__isset($varName) || isset($this->data[$varName]) );
	}

	/**
	 * Set property value
	 * @access public
	 * @param STRING $name
	 * @param MIXED $value
	 * @return Subscription
	 */
	public function set($name, $value)
	{
		if(is_a($name, 'DBColumn'))
		{
			/* @var $name DBCOlumn */
			$name = $name->getColumnName();
		}
		if( $name == $this->PKey )
		{
			$this->setId($value);
		}
		else
		{
			$this->setProperty($name, $value);
		}
		return $this;
	}

	/**
	 * override magic setter
	 * @access public
	 * @param STRING $name
	 * @param MIXED $value
	 * @return Subscription
	 */
	public function __set($name, $value)
	{
		if( isset($this->data[$name]))
		{
			if($this->data[$name] != $value )
			{
				if($this->exists())
				{
					$this->isModified = true;
				}
			}
		}
		$this->data[$name] = $value;
		return $this;
	}

	/**
	 * The ObjectFactory method
	 * @access public
	 * @static
	 * @param int|string $Id
	 *
	 * @return DataBaseAccessor
	 */
	static public function getInstance( $Id=null, $class = null, $params = null )
	{
		if(empty($params))
		{
			$params = [];
		}
		if(empty($params[ObjectFactory::ATTRIBUTES]))
		{
			$params[ObjectFactory::ATTRIBUTES] = [];
		}
			//$params[ObjectFactory::ATTRIBUTES][AppConst::TYPE_DBCONNECTION] = DBConnection::getConnectionOfType(MySQL::MYSQL, AppConst::MYSQL_RW);
		/* @var $instance DataBaseAccessor */
		$instance =  parent::getInstance($Id, $class, $params);
		if(!empty($Id))
		{
			if($instance->getId() != $Id)
			{
				$instance->setId($Id);
			}
			return $instance->read();
		}
		
		return $instance;
	}



	/**
	 * Returns Instance of Object for passed filters
	 *
	 * @param array $filters
	 * @access public
	 * @static
	 * @return DataBaseAccessor
	 */
	static public function getInstanceBy( array $filters=null, $class = null)
	{
		//check if object exists in the instances array
		// if it does exist, returns it
		$obj = parent::getInstanceBy($filters, $class);
		//if object doesn't exist in instances array, create a new object and query the database
		if(empty($obj))
		{
			$obj = parent::getInstance(null, $class);
			$obj = $obj->read($filters);
		}
		return $obj;
	}

	/**
	 * Read single data record from database
	 * @access public
	 * @param $filters ARRAY (opt.)
	 * @return Child
	 */
	public function read(array $filters=null )
	{
		$params = new QueryParameter();
		$params->baseTable = $this->dbTable;
		if( empty($filters))
		{
			if($this->PKey != ConcreteDBAccessor::IGNORE_PKEY)
			{
				$filters = 
				[
					[
						DBColumn::getInstance()->setColumnName($this->PKey), 
						$this->getId(), 
						'='
					]
				];
			}
		}
		//var_dump($filters);
		$params->filters = $filters;
		$sqlToExec = self::getSQL($params);
		$sql = $sqlToExec['sql'];
		$pdoParams = $sqlToExec['pdoParams'];
		$sql .= " LIMIT 1";
		$res = $this->getDBConnection()->executePreparedQuery($sql, $pdoParams);
		$results = $res->fetch(PDO::FETCH_ASSOC);
		if(empty($results))
		{
			$this->exists = false;
			foreach($filters as $filter)
			{
				$this->set($filter[0], $filter[1]);
			}
		}
		else
		{
			$this->exists = true;
			foreach($results as $key => $value)
			{
				if($key == $this->PKey)
				{
					$this->setId($value);
				}
				else
				{
					$this->set($key, $value);
				}
			}
		}
		$this->isModified = false;
		return $this;
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
		$stmt = "INSERT INTO `".$this->dbTable.'` ';
		if(empty($exclusions))
		{
			$exclusions = [$this->getPKey()];
		}
		else
		{
			if(!in_array($this->getPKey(), $exclusions))
			{
				$exclusions[] = $this->getPKey();
			}
		}
		$data = $this->data;
		foreach($exclusions as $exclusion)
		{
			if(isset($data[$exclusion]))
			{
				unset($data[$exclusion]);
			}
		}
		$stmt .= Utils::sqlPrepareSet($data, $exclusions, true);
		if($updateOnDuplicate)
		{
			$stmt .= ' ON DUPLICATE KEY UPDATE ';
			$stmt .= Utils::sqlPrepareSet($data, $exclusions);
		}
		if(!$this->getDBConnection()->executePreparedQuery($stmt, $data))
		{
			/* @var $asdf DBConnection */
			throw new UbException( mysql_error($this->getDBConnection()->getResource()) . "\n" . $stmt, mysql_errno($this->getDBConnection()->getResource()));
		}
		$this->isModified = false;
		$newId = $this->getDBConnection()->getResource()->lastInsertId(); 
		$this->setId((int)$newId);
		if($updateOnDuplicate)
		{
			$filters = array();
			foreach($this->data as $attributeKey => $attributeValue)
			{
				if($this->getPKey() != $attributeKey)
				{
					/* @var $dbColumn DBColumn */
					$dbColumn = DBColumn::getInstance();
					$filters[] = 
					[
						$dbColumn->setTable($this->dbTable)->setColumnName($attributeKey), 
						$attributeValue, 
						'='
					];
				}
			}
			$this->read($filters);
		}
		else
		{
			$this->read();
		}
		return $this;
	}

	/**
	 * Update data to database
	 * @access public
	 * @return BOOLEAN
	 */
	public function update()
	{
		if( !$this->isModified ) return true;
		if( empty($this->Id) ) return false;

		$sql = "UPDATE `".$this->dbTable."` SET ";
		$sql .= Utils::sqlPrepareSet($this->data, array($this->PKey));
		$sql .= " WHERE `".$this->PKey."` = " . (int)$this->Id;

		return $this->db_connection->executeQuery($sql);
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

	/**
	 * Sets the objects db connection
	 * @param string $identifier
	 */
	public function setConnection($identifier = AppConst::MYSQL_RW)
	{
		$this->db_connection = DBConnection::getByIdentifier($identifier);
		return $this;
	}
	
	public function setConnectionWithObject($connection)
	{
		$this->db_connection = $connection;
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
	static public function readListBy( $filters = null, $limit=0, $orderBy='', $offset=0, $class= null )
	{
		$pdoParams = [];
		$orderBy = Utils_Format::prepareInput($orderBy);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$results = array();
		$class = empty($class)?get_called_class():$class;
		$obj =  call_user_func_array($class .'::getInstance', array(null, $class));
		if( empty($filters) ) 
		{
			$filters = 	[
							[
								1, 1, '='
							]
						]; // get all
		}

		$sqlLimit = ($limit > 0) ? ' LIMIT ' . $offset . ',' . $limit : '';
		/* @var $obj Coupon */
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `".$obj->getDbTable()."` WHERE ";
		$sqlToExec = Utils::sqlPrepareFilters($filters);
		$pdoParams = array_merge($pdoParams, $sqlToExec['pdoParams']);
		$sql .= $sqlToExec['sql'];
		if( !empty($orderBy) )
		{
			$sql .= " ORDER BY " . $orderBy;
		}
		$sql .= $sqlLimit;
		/* @var $obj DataBaseAccessor */
		
		$res = $obj->getDBConnection()->executePreparedQuery($sql, $pdoParams);
		if( !$res ){
			unset($obj);
			return $results;
		}

		// readout found rows

		$obj->getFoundRows($class);

		foreach( $res as $row )
		{
			$newObj = clone $obj;
			$newObj->exists = true;
			foreach( $row as $k => $v )
			{
				if($k == $newObj->PKey)
				{
					$newObj->setId($v);
				}
				else
				{
					$newObj->set($k, $v);
				}
			}
			$results[] = $newObj;
		}
		return $results;
	}

	/**
	 * Gets the result of a SQL query generated accordingly to the
	 * parameters filters, groupByFields, additionalFields, aggregateFunctionParameter, for, type, having
	 *
	 * @param QueryParameter $params
	 * @access public
	 * @static
	 * @return array
	 */
	static public function getResults($params)
	{
		if(isset($params->newTable))
		{
			$res = self::queryGettingDescription($params);
		}
		else
		{
			$res = self::executeQuery($params);
		}
		return $res;
	}

	/**
	 * @param QueryParameter $params
	 * @param DBConnection $db_connection
	 */
	static protected function executeQuery($params)
	{
		$sqlToExec = self::getSQL($params);
		$sql = $sqlToExec['sql'];
		$pdoParams = $sqlToExec['pdoParams'];
		if(is_null($params->db_connection))
		{
			$params->db_connection = DBConnection::getByIdentifier();
		}
		return $params->db_connection->executePreparedQuery($sql, $pdoParams);
	}

	/**
	 * Generates the query
	 * And puts the description in $params->queryFieldsDescription
	 * @param QueryParameter $params
	 */
	static private function queryGettingDescription($params, $db_connection = null)
	{
		$sql = self::getSQL($params);
		$result = mysql_query($sql, $params->db_connection->getResource());
		$sql = 'DESCRIBE temp_'.$params->baseTable;
		$result = mysql_query($sql, $params->db_connection->getResource());
		$params->queryFieldsDescription = array();
		while(($fieldDescription = mysql_fetch_assoc($result))!= false)
		{
			$params->queryFieldsDescription[] = $fieldDescription;
		}
		$sql = 'SELECT * FROM temp_'.$params->baseTable;
		if(is_null($db_connection))
		{
			$db_connection = MySQL::getInstance(null, null, array(MySQL::CONNECTION_NAME => AppConst::MYSQL_RW));
		}
		return $db_connection->fetchAssoc($sql);
	}

	/**
	 * Gets the SQL query generated accordingly to the
	 * parameters in $params
	 * More info in Documents/PartnerProgram/queryGenerator.docx
	 *
	 * @param QueryParameter $params
	 * @access public
	 * @static
	 * @return array
	 */
	static public function getSQL($params)
	{
		$sqlToExec = ['sql' => '', 'pdoParams' => []];
		if( empty($params->filters))
		{
			$params->filters = array( array('1', 1, '=') ); // get all
		}
		if(empty($params->baseTable))
		{
			$params->baseTable = 'person';
		}
		$resultsBaseTable = Utils::sqlPrepareBaseTable($params);
		$table = $resultsBaseTable['table'];
		$sql = $resultsBaseTable['sql'];
		if(!isset($params->db_connection))
		{
			$params->db_connection = DBConnection::getInstance();
		}
		if(!empty($params->jointFields))
		{
			$join = Utils::sqlPrepareJoin($params->jointFields, $table);
			foreach($join as $joinStmt)
			{
				$sql .=  $joinStmt;
			}
		}
		$sql .= " WHERE ";
		$whereStatement = Utils::sqlPrepareFilters($params->filters);
		$sql .= '('.$whereStatement['sql'].')';
		$sqlToExec['pdoParams'] = array_merge($sqlToExec['pdoParams'], $whereStatement['pdoParams']);
		if(!empty($params->groupByFields))
		{
			$sql = Utils::sqlPrepareGroupBy($params->groupByFields, $sql); // get all
		}
		$aggregateStmt = '';
		if(!empty($params->operationType))
		{
			switch($params->operationType)
			{
				case self::FUNCTION_COUNT_DISTINCT:
					$aggregateStmt .= 'COUNT (DISTINCT ';
					break;
				default:
					$aggregateStmt = $params->operationType.'(';
					break;
			}
			
			if(empty($params->aggregateFunctionParameter))
			{
				$params->aggregateFunctionParameter = '*';
				$aggregateStmt .= $params->aggregateFunctionParameter.')';
			}
			else
			{
				if(is_array($params->aggregateFunctionParameter))
				{
					$afStmt = Utils::sqlPrepareFunction($params->aggregateFunctionParameter[0], $params->aggregateFunctionParameter[1]);
					if(isset($params->aggregateFunctionParameter[2]))
					{
						$afStmt .= 'as '.$params->aggregateFunctionParameter[2];
					}
					$params->aggregateFunctionParameter = $afStmt;
				}
				elseif($params->operationType == self::FUNCTION_COUNT_DISTINCT)
				{
					$aggregateStmt.= '`';
				}
				$aggregateStmt .= $params->aggregateFunctionParameter.'`)';
			}
			
			if(isset($params->arithmeticOperatioOnResult))
			{
				if(isset($params->arithmeticOperatioOnResult[QueryParameter::ARITHMETIC_PARAMETER]))
				{
					$aggregateStmt .= $params->arithmeticOperatioOnResult[QueryParameter::ARITHMETIC_OPERATOR].$params->arithmeticOperatioOnResult[QueryParameter::ARITHMETIC_PARAMETER];
				}
				else
				{
					$aggregateStmt = $params->arithmeticOperatioOnResult[QueryParameter::ARITHMETIC_OPERATOR].$aggregateStmt;
				}

			}
			if(isset($params->operationResultName))
			{
				$aggregateStmt .= ' as '.$params->operationResultName;
			}
			else
			{
				$aggregateStmt .=  ' ';
			}
		}
		$additionalFieldsStmt = '';
		if(empty($params->groupByFields))
		{
			if(empty($params->additionalFields))
			{
				if(empty($params->operationType))
				{
					$additionalFieldsStmt = '*';
				}
			}
		}
		if(!empty($params->additionalFields))
		{
			if(!empty($params->operationType))
			{
				$additionalFieldsStmt = ', ';
			}
			$additionalFieldsStmt .= Utils::sqlPrepareAdditional($params->additionalFields);
		}
		$havingStmt = '';
		if(!empty($params->having))
		{
			$havingStmt = ' HAVING ';
			$havingStmt .= Utils::sqlPrepareFilters($params->having);
		}
		$orderByStmt = '';
		if(isset($params->orderBy))
		{
			$orderByStmt = Utils::sqlPrepareOrderBy($params->orderBy);
		}
		$limitStmt = '';
		if(isset($params->limit))
		{
			$limitStmt = ' LIMIT ';
			if(isset($params->limit[QueryParameter::LIMIT_FROM]))
			{
				$limitStmt .= $params->limit[QueryParameter::LIMIT_FROM].',';
			}
			else
			{
				$limitStmt .= '0, ';
			}
			$limitStmt .= $params->limit[QueryParameter::LIMIT_TO];
		}
		$temp = '';
		if(isset($params->newTable))
		{
			if($params->newTable)
			{
				$temp = 'CREATE TEMPORARY TABLE temp_'.$params->baseTable;
			}
		}
		$sqlToExec['sql'] = $temp.' SELECT '.$aggregateStmt.$additionalFieldsStmt.$sql.$havingStmt.$orderByStmt.$limitStmt;
		return $sqlToExec;
	}

	/**
	 * Readout the found rows for the readListBy-Method and save them in the
	 * self::$foundRows var
	 *
	 * @param ressource $ressource  MySQL ressource link
	 * @access protected
	 * @static
	 * @return void
	 */
	protected function getFoundRows($class )
	{
		$stmt = "SELECT FOUND_ROWS()";
		$dbConnection = $this->getDBConnection();
		/* @var $dbConnection DBConnection */
		foreach ($dbConnection->query($stmt) as $row) 
		{
			self::$foundRows[$class] = $row['FOUND_ROWS()'];
		}
	}

	/**
	 * Sets the attributes of the object using filters
	 *
	 * @param Array $filters
	 * @access public
	 * @static
	 * @return void
	 */
	public function setForInsertion($filters)
	{
		foreach($filters as $filter)
		{
			$this->set($filter[0], $filter[1]);
		}
		return $this;
	}

	public function showTablesNamed($name)
	{
		$sql = "SHOW TABLES LIKE '".$name."'";
		return $this->db_connection->fetchAssoc($sql);
	}

}
;