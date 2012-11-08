<?php
class DBColumn extends ObjectFactory
{
	private $columnName;
	
	/**
	 * 
	 * @var string | DBTable
	 */
	private $inTable;
	
	/**
	 *
	 * @var string
	 */
	private $inSchema = null;
	
	public function getColumnName()
	{
		return $this->columnName;
	}
	
	/**
	 * 
	 * @param String $name
	 * @return DBColumn
	 */
	public function setColumnName($name)
	{
		$this->columnName = $name;
		return $this;
	}
	
	/**
	 * @return DBTable
	 */
	public function getTable()
	{
		return $this->inTable;
	}
	
	/**
	 * 
	 * @param DBTable $table
	 * @return DBColumn
	 */
	public function setTable($table)
	{
		$this->inTable = $table;
		return $this;
	}
	
	public function getDbSchema()
	{
		return $this->inSchema;
	}
	
	public function setSchema($schema)
	{
		$this->inSchema = $schema;
		return $this;
	}
}
?>