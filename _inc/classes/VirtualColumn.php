<?php
/**
 *
 * @author Diego Saa <diegueins680@hotmail.com>
 *
 * @uses MySQL
 *
 */
class VirtualColumn extends DataBaseAccessor
{
    static $typesWithLength = array('decimal', 'int', 'varchar');

    private $isPrimaryKey = false; // Bool tells if this column is part of the primary key
    private $isNullable = 'NO';

    public function getIsPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

    public function getIsNullable()
    {
        return $this->isNullable;
    }

    public function setIsPrimaryKey($isPrimaryKey)
    {
        $this->isPrimaryKey = $isPrimaryKey;
    }

    public function setIsNullable($isNullable)
    {
        $this->isNullable = $isNullable;
    }

    protected function setConfig()
    {
        $this->setPKey('column_id');
        $this->setDbTable('column');
        $this->setConnection(AppConst::MYSQL_RW);
    }

    /**
     * If column exists in db, updates it's length if needed
     * If a table contains it, it alters it's length
     * @param String $columnName
     * @param Mixed $value
     * @access public
     * @throws UbException
     */
    public function setValue($columnName, $type, $value)
    {
        /* @var $column VirtualColumn */
        $filters = array(array('name', $columnName, '='),
                         array('type', $type, '='));
        if($this->exists($filters))
        {
            $this->controlLength($value);
        }
        else
        {
            $this->setForInsertion($filters)
                 ->insert();
        }
        $this->set('value', $value);
    }

    /**
     *
     * @param QueryParameter $params
     */
    public function control($params)
    {
        if($this->exists())
        {
            $this->controlLength($params);
        }
        else
        {
            //$this->
            $this->insert();
            $table = VirtualTable::getInstanceBy(array(array('table_name', $params->repositoryName, '=')));
            $this->addToTable($table);
            $this->createInDbTable($table);
        }
    }

    /**
     *
     * @param Table $table
     */
    public function addToTable($table)
    {
        $tableId = $table->table_id;
        $columnTable = ColumnTable::getInstance();
        /* @var $columnTable ColumnTable */
        $columnTable->table_id = $tableId;
        $columnTable->column_id = $this->getId();
        $columnTable->insert();
    }

    /**
     *
     * @param Table $table
     */
    public function createInDbTable($table)
    {
        $stmt = 'ALTER TABLE `'.$table->table_name.'` ADD  `'.$this->name.'` '.$this->type;
        if($this->nullable == 'NO')
        {
            $stmt .= 'NOT';
        }
        $stmt .= 'NULL';
        if(isset($this->index))
        {
            $stmt .= 'ADD '.$this->index.' (`'.$this->table_name.'`)';
        }
        mysql_query($stmt, $this->getMySQLResource());
    }


    /**
     * Alters the length of the column if it needs to increase
     * (to avoid truncating data that is too large)
     * @param QueryParameter $params
     * @access public
     */
    public function controlLength($params)
    {
        $length = strlen((string)$params->attributeValue);
        if($length > $this->length)
        {
            $this->set('length', $length);
            $this->insert(null, true);
            $columnTables = ColumnTable::readListBy(array(array('column_id', $this->column_id, '=')));
            /* @*/
            foreach($columnTables as $columnTable)
            {
                /* @var $table Table */
                $table = VirtualTable::getInstance($columnTable->table_id);
                $table->alter($this->get('name'), $this);
            }
        }
        return $length;
    }

    public function setFields($results)
    {
        if(!isset($this->aggregate_field_name))
        {
            $this->aggregate_field_name = '';
            $result = $params->results[0];
            foreach($result as $attributeKey => $attributeValue)
            {
                $this->aggregate_field_name .= $attributeKey.', ';
            }
            $this->aggregate_field_name = trim($this->aggregate_field_name, ', ');
        }
    }
}
?>