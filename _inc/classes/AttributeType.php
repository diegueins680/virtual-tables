<?php
class AttributeType extends DataBaseAccessor
{
	/**
	 *
	 * @see DataBaseAccessor::setConfig()
	 */
	protected function setConfig()
	{
		$this->setPKey('attribute_type_id');
		$this->setDbTable(APPConst::TABLE_ATTRIBUTE_TYPE);
		$this->setDbConnectionIdentifier(AppConst::MYSQL_RW);
	}

	/**
	 * GetInstance
	 * @param MIXED $Id
	 * @return Stats
	 */
	public static function getInstance($Id=null, $class = null, $params = null)
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
	
	/**
	 * Inserts an attributeType for the specified table
	 * @param String $className
	 * @param String $attributeTypeName
	 * @param String $dataType
	 * @param Boolean $unique
	 * @param Boolean $nullable
	 * @param Boolean $pkey
	 */
	public function create($className, $attributeTypeName, $dataType = 'int', $unique = false, $nullable = true, $pkey = false)
	{
		$object = $className::getInstance();
		$attributeType = AttributeType::getInstance();
		$attributeType->set('table_id', $object->getVirtualDbTable()->getId())
						->set('attribute_type_name', $attributeTypeName)
						->set('data_type', $dataType)
						->set('unique', $unique)
						->set('nullable', $nullable)
						->set('pkey', $pkey);
		
		$attributeType->insert();
	}
}
?>