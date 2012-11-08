<?php
/**
 * DBTable
 *
 * @author Diego Saa <dsaa@onlineacg.com>
 * @copyright All rights reserved, (c) DubLi.com 2012
 * @package dubli.classes
 * @since 2012-04-11
 *
 * @uses MySQL
 *
 */
class DBTable extends DataBaseAccessor
{
	const TABLE_ID = 'table_id';
	
	const TABLE_NAME = 'table_name';
	
	private $columns; // array of columns in the table

	private $dbPrimaryKey; // Column pKey of the table being described by this object
	
	protected function setConfig()
	{
		$this->setPKey('table_id');
		$this->setDbTable(AppConst::TABLE_TABLE);
		$this->setDbConnectionIdentifier(AppConst::MYSQL_RW);
	}

	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 *
	 * @return Column
	 */
// 	public function getDbPrimaryKey()
// 	{
// 		if(!isset($this->dbPrimaryKey))
// 		{
// 			if($this->exists())
// 			{
// 				$filters = array(array('table_id', $this->table_id, '='), array('is_primary_key', 1, '='));
// 				$columnTable = ColumnTable::getInstanceBy($filters);
// 				$PKcolumn = Column::getInstanceBy(array(array('column_id', $columnTable->column_id, '=')));
// 				$this->setDbPrimaryKey($PKcolumn);
// 				$pKey = $PKcolumn->name;
// 				$this->setPKey($pKey);
// 			}
// 		}
// 		return $this->dbPrimaryKey;
// 	}

	public function setColumns($columns)
	{
		$this->columns = $columns;
	}

	/**
	 *
	 * @param Column $dbPrimaryKey
	 */
	private $inSchema = null;

	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 *
	 * @param String $name
	 * @return DBTable
	 */
	public function setTableName($name)
	{
		$this->tableName = $name;
		return $this;
	}

	/**
	 *
	 */
	public function getSchema()
	{
		return $this->inSchema;
	}

	/**
	 *
	 * @param String $table
	 * @return DBTable
	 */
	public function setSchema($schema)
	{
		$this->inSchema = $schema;
		return $this;
	}
	
	/**
	 *
	 * @param Column $dbPrimaryKey
	 */
	public function setDbPrimaryKey($dbPrimaryKey)
	{
		$this->dbPrimaryKey = $dbPrimaryKey;
		$this->primary_key = $dbPrimaryKey->column_id;
	}
	
	
	
	
	/**
	 * Creates a database table with the specified name and columns
	 *
	 * @param String $name
	 * @param Array $columns An array of Column
	 * @access public
	 */
	public function createTable($engine = 'InnoDB', $charset = 'utf8', $collation = 'latin1_swedish_ci', $autoIncrement = 1)
	{
		$stmt = "CREATE TABLE IF NOT EXISTS `".$this->table_name."` (";
		$stmt .= self::prepareColumnsStmt($this->columns);
		$stmt .=  ') ENGINE=InnoDB DEFAULT CHARSET='.$charset.' COLLATE='.$collation.' AUTO_INCREMENT='.$autoIncrement;
		$result = mysql_query($stmt, $this->getMySQLResource());
		$stmt = "ALTER TABLE  `".$this->table_name."` CHANGE  `".$this->getDbPrimaryKey()->name."`  `".$this->getDbPrimaryKey()->name."` INT( 10 ) NOT NULL AUTO_INCREMENT";
	
		if( !mysql_query($stmt, $this->getMySQLResource()) )
		{
			throw new UbException( mysql_error($this->getDBConnection()->getResource()) . "\n" . $stmt, mysql_errno($this->getDBConnection()->getResource()));
		}
	}
	
	/**
	 *
	 * @param String $name
	 * @param Array $columns An array of Column
	 * @access public
	 */
	public function insertTable()
	{
		$this->insert(null, true);
		$this->connectColumns($this->columns);
	}
	
	public function assureExistence()
	{
		$filters = array(array('table_name', $this->table_name, 'LIKE'),
				array('primary_key', $this->primary_key, 'LIKE'));
		if(!$this->exists($filters))
		{
			$this->insertTable();
		}
		if(count($this->showTablesNamed($this->table_name))==0)
		{
			$this->createTable();
		}
	}
	
	
	/**
	 * Creates entries in column_table for each column in the $columns array
	 *
	 * @param Array $columns An array of Column
	 * @access private
	 */
	private function connectColumns($columns)
	{
		foreach ($columns as $column)
		{
			/* @var $column Column */
			$filters = array(array('name', $column->name, 'LIKE'),
					array('type', $column->type, 'LIKE'));
			if(!$column->exists($filters))
			{
				$column->setForInsertion($filters)
				->insert(null, true);
			}
			$isPrimaryKey = false;
			if($this->primary_key == $column->getId())
			{
				$isPrimaryKey = 1;
			}
	
			$filters = array(array('table_id', $this->getId(), '='), array('column_id', $column->getId(), '='), array('is_primary_key', $isPrimaryKey));
			/* @var $columnTable ColumnTable */
			$columnTable = ColumnTable::getInstance();
			if(!$columnTable->exists($filters))
			{
				$columnTable->setForInsertion($filters)
				->insert(null, true);
			}
		}
	}
	
	/**
	 * Creates the columns part of a create table statement
	 *
	 * @param Array $columns An array of Column
	 * @access private
	 */
	private static function prepareColumnsStmt(array $columns)
	{
		$stmt = '';
		$pKeyStmt = 'PRIMARY KEY (';
		foreach($columns as $column)
		{
			/* @var $column Column */
			if($column->getIsPrimaryKey())
			{
				$pKeyStmt .= '`'.$column->name.'`, ';
			}
			$stmt .= '`'.$column->name.'` '.$column->desc.' ';
	
			if($column->getIsNullable() == 'NO')
			{
				$stmt .= 'NOT ';
			}
			$stmt .= 'NULL, ';
		}
		$pKeyStmt = trim($pKeyStmt, ', ').') ';
		return $stmt.$pKeyStmt;
	}
	
	/**
	 * Alters the specified column
	 *
	 * @param String $columnName
	 * @param Column $newColumn
	 * @access public
	 */
	public function alter($columnName, $newColumn)
	{
		$stmt = 'ALTER TABLE  `'.$this->table_name.'` CHANGE  ';
		foreach($newColumn as $columnAttributes)
		{
			$stmt .= "`".$columnName."`  `".$newColumn->name."` ".$newColumn->type."( ".$newColumn->length." )";
		}
	}
	
	/**
	 *
	 * @param QueryParameter $params
	 */
	public function setColumnsFromResults($params)
	{
		/* @var $pKeyColumn Column */
		$columns = array();
		$pKeyColumn = DBColumn::getInstance();
		$pKeyName = $params->repositoryName.'_id';
		$pKeyType = 'int';
		$pKeyLength = 10;
		$pKeyDesc = $pKeyType.'('.$pKeyLength.') unsigned';
	
		$filters = array(array('name', $pKeyName, '='),
				array('desc', $pKeyDesc, '='),
				array('type', $pKeyType),
				array('length', $pKeyLength)
		);
		$pKeyColumn->setForInsertion($filters)
		->setIsPrimaryKey(true);
		if(!$pKeyColumn->exists($filters))
		{
			$pKeyColumn->insert();
		}
		$columns[] = $pKeyColumn;
		$this->setDbPrimaryKey($pKeyColumn);
		foreach($params->queryFieldsDescription as $fieldDescription)
		{
			$column = DBColumn::getInstance();
			$column->name = $fieldDescription['Field'];
			$column->desc = $fieldDescription['Type'];
			$typeAndOtherAttribs = explode('(', $column->desc);
			$column->type = $typeAndOtherAttribs[0];
			if(in_array($column->type, DBColumn::$typesWithLength))
			{
				$possiblyLengthAndOtherAttribs = explode(')', $typeAndOtherAttribs[1]);
				$possiblyLengthAndOtherAttribs = array_merge($possiblyLengthAndOtherAttribs, explode(',', $typeAndOtherAttribs[1]));
				$i = 1;
				foreach($possiblyLengthAndOtherAttribs as $searchingLengthAttribute)
				{
					if(is_numeric($searchingLengthAttribute))
					{
						$column->length = (int)$searchingLengthAttribute;
						break;
					}
				}
			}
			else
			{
				$column->length = 0;
			}
			$column->nullable = $fieldDescription['Null'];
			$filters = array();
			foreach($column->__get('data') as $attributeKey => $attributeValue)
			{
				if($attributeKey != $column->getPKey())
				{
					$filters[] = array($attributeKey, $attributeValue, '=');
				}
			}
	
			/* @var $column Column */
			if(!$column->exists($filters))
			{
				$column->insert(null, true);
			}
	
			$columns[] = $column;
		}
		/* @var $pKeyColumn Column */
		$this->columns = $columns;
	}
}
?>