<?php
/**
 * ConcreteDBAccessor - DubLi package subscriptions
 *
 * @author Diego Saa <dsaa@dubli.com>
 * @copyright All rights reserved, (c) DubLi.com 2012
 * @package dubli.classes
 * @since 2012-04-11
 *
 * @uses DataBaseAccessor
 *
 */
class ConcreteDBAccessor extends DataBaseAccessor
{
    const IGNORE_PKEY = 'ignore';

    protected function setConfig()
    {
        $this->setPKey(self::IGNORE_PKEY);
    }

    public function reConfig($pKey, $dbTable, $connection = AppConst::MYSQL_RW)
    {
        $this->setPKey($pKey);
        $this->setDbTable($dbTable);
        $this->setConnection($connection);
        $this->setId($this->getId());
    }
}
?>