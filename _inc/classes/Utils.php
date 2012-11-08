<?php
/**
 * Utilities
 * @author Diego Saa <cuco.saa@gmail.com>
 */

class Utils
{

    /**
     * Types of fields
     * @var int
     */
    const TYPE_NUMERIC_KEY = 0;
    const TYPE_DATE_START_FIELD = 1;
    const TYPE_DATE_END_FIELD = 2;
    const TYPE_EMAIL_ADDRESS_FIELD = 3;
    const TYPE_ID_FIELD = 4;

    /**
     * Property/Key -name used for sorting
     * Used by self::sortObjectsBy()
     * @var string
     */
    public static $sortObjectsByProperty = 'order';

    /**
     * Reverse sort
     * Used by self::sortObjectsBy()
     * @var bool
     */
    public static $sortObjectsByReverse = false;

    private function __construct(){}


    /**
     * Prepare a MySQL SET statement part (for UPDATE or INSERT with "... SET `name` = 'key', `name2` = 'key2', ..."
     *
     * @param array $values	Array of key=>values pairs
     * @param array $exclusions Optional Array of key name we need to exclude from the returning statement
     * @return string
     */
    static public function sqlPrepareSet( array $values, array $exclusions=null, $valuesFormat = false)
    {
    	$statement = '';
        if( null === $exclusions ) $exclusions = array();
        $exclusions = array_merge( array('date_last_modified', 'date_lastmodified', 'last_modified'), $exclusions);
       
        if($valuesFormat)
        {
        	$keysStatment = '(';
        	$valuesStatement = '(';
        	foreach( $values as $key => $val )
        	{
        		$keysStatment .= '`'.$key.'`, ';
        		$valuesStatement .= ':'.$key.', ';
        	}
        	$keysStatment = trim($keysStatment, ', ').')';
        	$valuesStatement = trim($valuesStatement, ', ').')';
        	$statement .= $keysStatment.' VALUES '.$valuesStatement;
        }
        else
        {
        	foreach( $values as $key => $val )
        	{
        		$statement .= '`'.$key.'` = :'.$key.', ';
        	}
        	$statement = trim($statement, ', ');
        }
        return $statement;
    }

    /**
     * Prepare a MySQL aggregate function statement part
     *
     * @access public
     * @param array $group	arrays of elements in the form table => field
     * @param string $sql
     * @param string $type
     * @static
     * @return string
     */
    static public function sqlPrepareGroupBy( $groupParams, $sql )
    {
        $group = self::convert2DArrayToAssoc($groupParams);
        if(isset($group))
        {
            $selectStatment = '';
            $fieldArrayForGBStmt = array();
            foreach( $group as $table => $fields )
            {
                if(is_array($fields[0]))
                {
                    $function = array_shift($fields[0]);
                    $selectStatment .= self::sqlPrepareFunction($function, array_shift($fields[0])).' as '.$table.', ';
                    $fieldArrayForGBStmt['functions'] = array($table);
                }
                else
                {
                    foreach($fields as $field)
                    {
                        $selectStatment .= '`'.$table.'`.`'.$field.'`, ';
                        if(isset($fieldArrayForGBStmt[$table]))
                        {
                            $fieldArrayForGBStmt[$table][] = $field;
                        }
                        else
                        {
                            $fieldArrayForGBStmt[$table] = array($field);
                        }
                    }
                }
            }
            $selectStatment = trim($selectStatment, ', ');
            $GBstmt = '';
            foreach($fieldArrayForGBStmt as $table => $fields)
            {
                foreach($fields as $field)
                {
                    if($table=='functions')
                    {
                        $GBstmt .= '`'.$field.'`, ';
                    }
                    else
                    {
                        $GBstmt .= '`'.$table.'`.`'.$field.'`, ';
                    }
                }
            }
            $GBstmt = trim($GBstmt, ', ');
            return ', '.$selectStatment.$sql.' GROUP BY '.$GBstmt;
        }
    }

    static public function sqlPrepareOrderBy( $orderParams )
    {
        $orderByStmt = ' ORDER BY ';
        foreach($orderParams as $orderParam)
        {
            $orderByStmt .= $orderParam[QueryParameter::FIELD_NAME];
            if(isset($orderParam[QueryParameter::TYPE]))
            {
                $orderByStmt .= ' '.$orderParam[QueryParameter::TYPE];
            }
            $orderByStmt .= ', ';
        }
        $orderByStmt = trim($orderByStmt, ', ');
        return $orderByStmt;
    }
    
    static public function sqlPrepareBaseTable(&$params)
    {
    	/* @var $params QueryParameter */
    	$returnArray = ['sql' => '', 'table' => ''];
    	switch(true)
    	{
    		case is_string($params->baseTable):
    			$returnArray['table'] = $params->baseTable;
    			$returnArray['sql'] = ' FROM `'.$returnArray['table'].'` ';
    			break;
    		case is_a($params->baseTable, 'DataBaseAccessor'):
    			$returnArray['table'] = $params->baseTable->getDbTable();
    			$returnArray['sql'] = ' FROM `'.$returnArray['table'].'` ';
    			$params->db_connection = $params->baseTable->getDBConnection();
    			break;
    		case is_array($params->baseTable):
    			$returnArray['sql'] = ' FROM ';
    			$argument = $params->baseTable[QueryParameter::STATEMENT];
    			if(!$params->baseTable[QueryParameter::PARAM_CONSTANT])
    			{
    				$returnArray['sql'].= ' ('.$argument.')';
    			}
    			else
    			{
    				$returnArray['sql'].= $argument;
    			}
    	
    	
    			if(isset($params->baseTable[QueryParameter::ALIAS]))
    			{
    				$returnArray['table'] = $params->baseTable[QueryParameter::ALIAS];
    				$returnArray['sql'] .= ' as '.$returnArray['table'];
    			}
    			break;
    		case is_a($params->baseTable, 'DBTable'):
    			$returnArray['table'] = $params->baseTable->getSchema() . $params->baseTable->getTableName();
    			$returnArray['sql'] = ' FROM '.$params->baseTable->getSchema() . '.`'.$returnArray['table'].'` ';
    			break;
    	}
    	return $returnArray;
    }


    /**
     * Creates a join statement section
     *
     * @param array $joinArray	Array of arrays with key,values
     * @param string $excludeTable	Table that is already in the FROM part of the sql statement
     * @param string $type Type of JOIN (INNER, OUTER, LEFT...)
     * @access public
     * @return Array
     * @static
     */
    static public function sqlPrepareJoin($joinArray, $excludeTable)
    {
        $excludeTables = array($excludeTable);
        $result = Array();
        foreach($joinArray as $conditionsArray)
        {
            $resultTable = '';
            $on = '(';
            $type = '';
            foreach($conditionsArray as $tableArray)
            {
                if(is_string($tableArray))
                {
                    if($tableArray == QueryParameter::TYPE)
                    {
                        array_shift($conditionsArray);
                        $type = array_shift($conditionsArray);
                    }
                }
                elseif(is_array($tableArray))
                {
                    $tableArray = self::convert2DArrayToAssoc($tableArray);
                    
                    foreach($tableArray as $joinTable => $joinFields)
                    {
                        if($joinTable != QueryParameter::PARAM_CONSTANT)
                        {
                            if(!in_array($joinTable, $excludeTables))
                            {
                                $excludeTables[] = $joinTable;
                                if(!array_key_exists($joinTable, $result))
                                {
                                	if(is_array($tableName = reset($joinFields)))
                                	{
                                		reset($joinFields);
                                		$first_key = key($joinFields);
                                		$resultTable .= '`'.$first_key.'` '.$joinTable.', ';
                                	}
                                	else
                                	{
                                		$resultTable .= '`'.$joinTable.'`, ';
                                	}
                                }
                            }
                        }
                        foreach($joinFields as $joinField)
                        {
                            if($joinTable == QueryParameter::PARAM_CONSTANT)
                            {
                                if(is_numeric($joinField))
                                {
                                    $on .= $joinField .' = ';
                                }
                                elseif(is_string($joinField))
                                {
                                    $on .= "'".$joinField."' = ";
                                }
                            }
                            else
                            {
                            	if(is_array($joinField))
                            	{
                            		foreach($joinField as $jf)
                            		{
                            			$on .= $joinTable.'.`'.$jf.'` = ';
                            		}
                            	}
                            	else
                            	{
                            		$on .= '`'.$joinTable.'`.`'.$joinField.'` = ';
                            	}
                            }
                        }
                    }
                    $on = trim($on, ' = ');
                    $on .= ' AND ';
                }
            }
            $on = trim($on, ' AND ').') ';
            $resultTable = trim($resultTable, ', ');
            $result[$resultTable] = ' '.$type.' JOIN '.$resultTable.' ON '. $on;
        }
        return $result;
    }

    /**
     * Creates a the part of a SQL for additional query fields
     *
     * @param array $additionalFields	Array of arrays with any of the next forms:
     * - array(element[0,0] = column name,
     *         element[0,1] = table name)
     *
     * - array(
     *         element[0,0] = result column name
     *         element[0,1] = 'function',
     *         element[0,2] = function name (ex. AVG, SUM, MIN, etc),
     *         element[0,3] = array of function parameters (may be other functions))
     *
     * - array(element[0,0] = result column name
     *         element[0,0] = 'constant',
     *         element[0,1] = value
     *
     * @return string
     */
    static public function sqlPrepareAdditional($additionalFields)
    {
        $fields = '';
        $additionalFields = self::convert2DArrayToAssoc($additionalFields);
        foreach( $additionalFields as $table => $columns )
        {
            while(($column = array_shift($columns))!= false)
            {
                if(is_array($column))
                {
                    $function = array_shift($column);
                    $fields .= self::sqlPrepareFunction($function, array_shift($column)).' as '.$table.', ';
                }
                elseif ($column == QueryParameter::PARAM_CONSTANT)
                {
                    $fields .= array_shift($columns).' as '.$table.', ';
                }
                else
                {
                    $fields .= '`'.$table.'`.`'.$column.'`, ';
                }
            }
        }
        return trim($fields, ', ');
    }

    /**
     * Creates the filtering portion of a SQL query
     * @param array $filters
     * @return string
     */
    static public function sqlPrepareFilters($filters = null)
    {
    	$statement = ['sql' => '', 'pdoParams' => []];
    	foreach( $filters as $filter)
    	{
    		$operator = !empty($filter[2]) ? $filter[2] : '=';
    		$op = !empty($filter[3]) ? $filter[3] : 'AND';
    		foreach($filter as $filterParamKey => $filterParam)
    		{
    			if($filterParamKey < 2)
    			{
    				$paramName = md5(uniqid(rand(), true));
    				switch(true)
    				{
    					case is_array($filterParam):
    						$filter[$filterParamKey] = self::sqlPrepareFunction($filterParam[0], $filterParam[1]);
    						break;
    					case is_int($filterParam):
    						switch($filterParamKey)
    						{
    							case 0:
    								$left = ':'.$paramName;
    							break;
    							case 1:
    								$right = ':'.$paramName;
    							break;
    						}
    						$statement['pdoParams'][$paramName] = $filterParam;
    						break;
    					case is_a($filterParam, 'DBColumn'):
    						/* @var $filterParam DBColumn */
    						$tableStmt = '';
    						if(!is_null($filterParam->getTable()))
    						{
    							switch(true)
    							{
    								case is_a($filterParam->getTable(), 'DataBaseAccessor'):
    									$tableStmt = '`'.$filterParam->getTable()->getDbTable().'`.';
    									break;
    								case is_string($filterParam->getTable()):
    									$tableStmt = '`'.$filterParam->getTable().'`.';
    									break;
    								case is_array($filterParam->getTable()):
    									$tableStmt = $filterParam->getTable()[0].'.';
    							}
    						}
    						/* @var $filterParam DBColumn */
    						switch($filterParamKey)
    						{
    							case 0:
    								$left = $tableStmt.'`'.$filterParam->getColumnName().'`';
    								break;
    							case 1:
    								$right= $tableStmt.'`'.$filterParam->getColumnName().'`';
    								break;
    						}
    						break;
    					case is_a($filterParam, 'DateTime'):
    						switch($filterParamKey)
    							{
    								case 0:
    									$left = ':'.$paramName;
    									break;
    								case 1:
    									$right = ':'.$paramName;
    									break;
    							}
    							/* @var $filterParam DateTime */
    							$statement['pdoParams'][$paramName] = $filterParam->format(AppConst::MYSQL_DATETIME_FORMAT);
    							break;
    					default:
    						/*while(empty($paramName))
    						 {
    						$paramName = substr(md5(uniqid(rand(), true)),0,10);
    						}*/
    						switch($filterParamKey)
    						{
    							case 0:
    								$left = ':'.$paramName;
    								break;
    							case 1:
    								$right = ':'.$paramName;
    								break;
    						}
    						$statement['pdoParams'][$paramName] = $filterParam;
    						break;
    				}
    			}
    		}
    		$statement['sql'] .= ' '  . trim($op) . ' ' . $left . ' ' . $operator . ' '.$right;
    	}
    	$processedFilters = [];

    	if( empty($filters) ) return ' 1 = 1 ';
    	$statement['sql'] = trim($statement['sql'], ' ,');
    	$statement['sql'] = preg_replace('/(^AND )|( AND$)/', '', $statement['sql']);
    	$statement['sql'] = preg_replace('/(^OR )|( OR$)/', '', $statement['sql']);
    	$statement['sql'] = trim($statement['sql'], ' ,');
    	return $statement;
    }

    /**
     * Creates the statement for a SQL function
     *
     * @param string $function The name of the SQL function (SUM, COUNT, etc)
     * @param Array $params The parameters for the function
     * @return string
     */
    static public function sqlPrepareFunction($function, $params)
    {
        $stmt = '';
        $infix = false;
        if(in_array($function, QueryParameter::$infixFunctions))
        {
            $infix = true;
            $stmt = '(';
        }
        foreach($params as $param)
        {
            if(is_array($param))
            {
                $stmt .= self::sqlPrepareFunction($param[0], $param[1]);
            }
            else
            {
                $stmt .= $param;
            }
            if($infix)
            {
                $stmt .= ' '.$function.' ';
            }
            else
            {
                $stmt .= ', ';
            }
        }

        if($infix)
        {
            $stmt = trim($stmt, ' '.$function.' ');
            $stmt .= ')';
        }
        else
        {
            $stmt = trim($stmt, ', ');
            $stmt = $function.'('.$stmt.')';
        }
        return $stmt;
    }

    /**
     * Returns an array with the keys of an array
     *
     * @param array $array
     * @return array
     */
    static public function getArrayOfKeys($array)
    {
        $resultArray = array();
        foreach($array as $key => $value)
        {
            $resultArray[] = $key;
        }
        return $resultArray;
    }

    /**
     * return a unique generated voucher code
     *
     * @param string $prefix   (Optional, def:'DBL')
     * @param string $part2	(Optional, def:'')
     * @param int $randomLength   (Optional, def:8)
     * @return STRING
     */
    static public function genVoucherCode($prefix='DBL', $part2='', $randomLength=8)
    {
        $part1 = (string)$prefix;
        $part2 = (string)$part2;
        $part3 = strtoupper(App::$ENGINE);
        $part4 = date('dmy');

        $chars = array_merge( range('0','9'), range('a','z'), range('A', 'Z'));

        //Seed the better random number generator
        mt_srand( (double)microtime() * 1000000 );

        for($i=1; $i<=(count($chars)*2); $i++)
        {
            $swap = mt_rand(0, count($chars)-1);
            $tmp = $chars[$swap];
            $chars[$swap] = $chars[0];
            $chars[0] = $tmp;
        }

        $part5 = substr(implode('',$chars), 0, $randomLength);

        $vouchercode = $part1 . '-' . $part2 . $part3 . $part4 . $part5;

        return $vouchercode;
    }

    /**
     * Sort array of Object's by given property/key
     * @param array $objects Array of objects
     * @param string $sortKey
     * @return array
     */
    public static function sortObjectsBy( &$objects, $sortKey='order' )
    {
        if( !is_array($objects) || empty($objects) || count($objects) == 1 ) return $objects;
        reset($objects);

        Utils::$sortObjectsByProperty = (string)$sortKey;
        $_prop = current($objects)->{Utils::$sortObjectsByProperty};

        if( !isset($_prop) ) return $objects;
        unset($_prop);

        if(!function_exists('_sortbyProp')) {
            function _sortbyProp($a, $b)
            {
                $p1 = $a->{Utils::$sortObjectsByProperty};
                $p2 = $b->{Utils::$sortObjectsByProperty};
                $p1 = isset($p1) ? $p1 : 0;
                $p2 = isset($p2) ? $p2 : 0;

                return ( Utils::$sortObjectsByReverse ? ($p1 < $p2) : ($p1 > $p2) );
            }
        }

        // maintain index association if associated array
        if( !is_int(key($objects)) ) uasort($objects, '_sortbyProp');
        else usort($objects, '_sortbyProp');
    }

    /**
     * Creates a unique Subscription Id
     * @param User $user
     * @param DubliPackage $package
     * @param string $prefix    Def:DC; optional prefix for string id
     * @return string
     */
    public static function genSubscriptionId( User $user, DubliPackage $package, $prefix='DC')
    {
        $subsId = $prefix . App::$ENGINE;
        $subsId .= $user->getId();
        $subsId .= 'P';
        $subsId .= $package->getId();
        $subsId .= date('yz');

        $subsId = strtoupper($subsId);

        return $subsId;
    }

    /**
     * Determines the data type, based on the field's name
     *
     * @param MIXED $value
     * @return CRPInvitation
     */
    public static function getFieldType($dbName)
    {
        $patterns = array(	'/_id$/',
            '/(?i)(date.*start|start.*date|date.*from|from.*date|period.*from)/',
            '/(?i)(date.*end|end.*date|date.*to|to.*date|period.*to)/',
            '/_email_address/',
            '/^\d+$/');
        foreach($patterns as $pattern)
        {
            if(preg_match($pattern, $dbName) >= 1 )
            {
                switch( $pattern )
                {
                    case '/_id$/':
                        return self::TYPE_ID_FIELD;
                    case '/(?i)(date.*start|start.*date|date.*from|from.*date|period.*from)/':
                        return self::TYPE_DATE_START_FIELD;
                    case '/(?i)(date.*end|end.*date|date.*to|to.*date|period.*to)/':
                        return self::TYPE_DATE_END_FIELD;
                    case '/_email_address/':
                        return self::TYPE_EMAIL_ADDRESS_FIELD;
                    case '/^\d+$/':
                        return self::TYPE_NUMERIC_KEY;
                }
            }

        }
    }

    /**
     * Removes a value from an array
     * @param Array $array
     * @param Mixed $val
     * @param string $prefix    Def:DC; optional prefix for string id
     * @return string
     */
    public static function remove_item_by_value($array, $val = '', $preserve_keys = false)
    {
        if (empty($array) || !is_array($array)) return false;
        if (!in_array($val, $array)) return $array;
        foreach($array as $key => $value)
        {
            if ($value == $val) unset($array[$key]);
        }
        return ($preserve_keys === true) ? $array : array_values($array);
    }

    /**
     * Resturns the truth value for a comparison between the two parameters, made with the specified operator.
     * @param Mixed $a The left operand
     * @param Mixed $b The right operand
     * @param string $operator    The operator
     * @return bool
     * TODO: Implement 'LIKE'
     */
    public static function compare($a, $b, $operator)
    {
        switch($operator)
        {
            case '===':
                return  $a === $b;
            case '==':
                return  $a == $b;
            case '!=':
                return $a != $b;
            case '<':
                return $a < $b;
            case '>':
                return $a > $b;
            case '<=':
                return $a <= $b;
            case '>=':
                return $a >= $b;
        }
    }

    /**
     * Converts a two dimensional array into an associative array
     * [
     * 		[
     * 			element[0,0],
     * 			.
     * 			.
     * 			. 
     * 			element[0,i]
     * 		],
     * 		.
     * 		.
     * 		.
     * 		[
     * 			element[n,0]
     * 			element[n,j]
     * 		]
     * ]
     * turns into
     * [
     * 		element[0,0] => 
     * 		[
     * 			element[0,1],
     * 			.
     * 			.
     * 			.
     * 			element[0,i]
     * 		],
     * 		.
     * 		.
     * 		.
     * 		element[n,0] => 
     *		[
	 *			element[n,1],
	 *			.
	 *			.
	 *			.
	 *			element[n,j]
	 *		]
	 *]
     * @param array $array
     * @return array
     */
    public static function convert2DArrayToAssoc( array $array )
    {
        $returnArray = array();
        foreach ($array as $element)
        {
            $key = array_shift($element);
            switch (true)
            {
            	case is_array($key):
            		$arrayKey = $key;
            		$key = $arrayKey[QueryParameter::ALIAS];
            		if(isset($returnArray[$key]))
            		{
	            		$returnArray[$key] = [$arrayKey[QueryParameter::STATEMENT] => array_merge($returnArray[$key], $element)];
            		}
            		else
            		{
	            		$returnArray[$key] = [$arrayKey[QueryParameter::STATEMENT] => $element];
            		}
            		break;
        		case is_string($key):
            	   	if(isset($returnArray[$key]))
            		{
	            		$returnArray[$key] = array_merge($returnArray[$key], $element);
            		}
            		else
            		{
	            		$returnArray[$key] = $element;
            		}
            		break;
        		case is_a($key, 'DBTable'):
        			$dbTable = $key;
        			/* @var $dbTable DBTable */
        			$key = $dbTable->getTableName();
        			if(isset($returnArray[$key]))
            		{
	            		$returnArray[$key] = array_merge($returnArray[$key], $element);
            		}
            		else
            		{
	            		$returnArray[$key] = 
	            		[
	            			$element
	            		];
            		}
            		break;
        	}
           
        }
        return $returnArray;
    }
}
