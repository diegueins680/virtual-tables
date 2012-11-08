<?php
/**
 * StatsInfo - DubLi package subscriptions
 *
 * @author Diego Saa <diego.saa@onlineacg.com>
 * @copyright All rights reserved, (c) Avanced Communications Group 2012
 * @package acg.classes
 * @since 2012-10-11
 *
 * @uses MySQL
 *
 */
class StatsInfo extends DataBaseAccessor
{
    protected function setConfig()
    {
        $this->setPKey('stats_info_id');
        $this->setDbTable(App::$DBTABLES['stats_info']);
        $this->setConnection(AppConst::MYSQL_RW);
    }

    /**
     * GetInstance
     * @param MIXED $Id
     * @return StatsInfo
     */
    public static function getInstance($Id=null, $class=null, $params=null){
    	return parent::getInstance($Id,__CLASS__, $params);
    }


    /**
     * GetInstanceBy
     * @param array $filters
     * @return StatsInfo
     */
    public static function getInstanceBy(array $filters = null, $class = null)
    {
    	return parent::getInstanceBy($filters, __CLASS__);
    }

    static public function readListBy( $filters = null, $orderBy='', $limit=0, $offset=0, $class= null ){
    	return parent::readListBy($filters,$orderBy,$limit,$offset,__CLASS__);
    }

    /**
     * @var array
     */
    private $resultRow;
    /**
     *
     * @param QueryParameter $params
     */
    public function setAggregateCategory($params)
    {
        if(!isset($params->groupByFields))
        {
            $this->set('aggregate_category', 'N/A');
        }
        else
        {
            foreach($params->groupByFields as $aggregateField)
            {
                if(is_array($aggregateField[1]))
                {
                    $this->set('aggregate_category', $this->aggregate_category.$this->resultRow[$aggregateField[0]].', ');
                }
                else
                {
                    if(isset($this->resultRow[$aggregateField[1]]))
                    {
                        $this->set('aggregate_category', $this->aggregate_category.$this->resultRow[$aggregateField[1]].', ');
                    }
                    else
                    {
                        $this->aggregate_category .= 'N/A, ';
                    }
                }
            }
        }
        $this->set('aggregate_category', trim($this->aggregate_category, ', '));
    }

    /**
     * Setter for resultRow
     * @param array $resultRow
     */
    public function setResultRow($resultRow){
    	$this->resultRow = $resultRow;
    }
}
?>