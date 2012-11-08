<?php
class RelationObjectIsType extends DataBaseAccessor
{
	protected function setConfig()
	{
		$this->setDbTable(AppConst::TABLE_TABLE);
		$this->setConnection(DBConnection::MYSQL, AppConst::MYSQL_RW);
		$this->setPKey(ConcreteDBAccessor::IGNORE_PKEY);
	}
}
?>