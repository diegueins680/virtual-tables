<?php
class DatetimeAttribute extends Attribute
{
	protected function setConfig()
	{
		$this->setPKey('datetime_attribute_id');
		$this->setDbTable(AppConst::TABLE_DATETIME_ATTRIBUTE);
		$this->setDbConnectionIdentifier(AppConst::MYSQL_RW);
	}

	/**
	 * GetInstance
	 * @param int|string $Id
	 * @param string $class
	 * @param array $params
	 * @return VarcharAttribute
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
	 * @return VarcharAttribute
	 */
	public static function getInstanceBy(array $filters=null, $class = null)
	{
		return parent::getInstanceBy($filters, __CLASS__);
	}
}
?>