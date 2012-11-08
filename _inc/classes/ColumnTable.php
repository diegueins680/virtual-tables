<?php
/**
 * ColumnTable - DubLi package subscriptions
 *
 * @author Diego Saa <dsaa@dubli.com>
 * @copyright All rights reserved, (c) DubLi.com 2012
 * @package dubli.classes
 * @since 2012-04-11
 *
 * @uses MySQL
 *
 */
class ColumnTable extends DataBaseAccessor
{
    protected function setConfig()
    {
        $this->setPKey('column_table_id');
        $this->setDbTable(App::$DBTABLES['column_table']);
       // $this->setConnection(MySQL::MYSQL_GLOBAL);
    }
}
?>