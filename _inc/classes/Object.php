<?php
class Object extends DataBaseAccessor
{
	const OBJECT_ID = 'object_id';
	
	protected function setConfig()
	{
		$this->setPKey('object_id');
		$this->setDbTable(AppConst::TABLE_OBJECT);
		$this->setDbConnectionIdentifier(AppConst::MYSQL_RW);
	}
	
	/**
	 * GetInstance
	 * @param MIXED $Id
	 * @return Stats
	 */
	public static function getInstance($Id=null, $class=null, $params=null)
	{
		return parent::getInstance($Id,__CLASS__, $params);
	}
	
	
	/**
	 * GetInstanceBy
	 * @param array $filters
	 * @return Stats
	 */
	public static function getInstanceBy(array $filters = null, $class = null)
	{
		return parent::getInstanceBy($filters, __CLASS__);
	}
}