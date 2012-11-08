<?php
class DBConnectionConfig extends DBAccessorObj
{
	protected $virtualDbTableName = AppConst::TYPE_DBCONNECTION_CONFIG;
	
	public function setConfig()
	{
		parent::setConfig();
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
		$instance = parent::getInstance($Id,__CLASS__, $params);
		/* @var $instance FieldFormat */
		return $instance;
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
?>