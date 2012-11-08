<?php
class ObjectAttribute extends Attribute
{
	protected function setConfig()
	{
		$this->setPKey(AppConst::TABLE_OBJECT_ATTRIBUTE.'table_id');
		$this->setDbTable(AppConst::TABLE_OBJECT_ATTRIBUTE);
	}
}
?>