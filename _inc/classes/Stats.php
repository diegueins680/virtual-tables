<?php
/**
 * Stats - ACG package stats
 *
 * @author Diego Saa <diego.saa@onlineacg.com>
 * @copyright All rights reserved, (c) acg.com 2012
 * @package acg.classes
 * @since 2012-10-30
 *
 * @uses DBAccesorObj
 *
 */
class Stats extends DataBaseAccessor
{
    /**
     *
     * @see DataBaseAccessor::setConfig()
     */
    protected function setConfig()
    {
        $this->setPKey('stats_id');
        $this->setDbTable(App::$DBTABLES['stats']);
        $this->setConnection(AppConst::MYSQL_RW);
    }

    /**
     * GetInstance
     * @param MIXED $Id
     * @return Stats
     */
    public static function getInstance( $Id=null, $class=null, $params=null)
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
    
    /*** METHODS TO GET STATS RESULTS ***/
    
    /**
     * Get the total leads loaded into Indosoft for a particular customer in a date-range
     * 
     * @param string $initialDate
     * @param string $endDate
     * @param Customer $customer
     */
    public function totalLeadsOffered(&$params)
    {
    	/* @var $params QueryParameter */
    	//$initialDate, $endDate, $customer, $count = true
    	var_dump($params);
    	$params->db_connection = DBConnection::getByIdentifier('Indosoft');
    	/*$params->filters = 
    	[
    		
    	];*/
    	$params->db_connection = DBConnection::getByIdentifier('Indosoft');
    	$ht_leads_master_table = DBTable::getInstance();
    	$ht_leads_master_table->setSchema(AppConst::SCHEMA_ICUDE)->setTableName(AppConst::ICUDE_LEADS_MASTER);
    	$params->baseTable = AppConst::CTI_CALL_LIST;
    	$params->jointFields =
    	[
    		[
    			[
    				[
    					$ht_leads_master_table,
    					'call_list_id'
    				],
    				[
    					AppConst::CTI_CALL_LIST,
    					'call_list_id'
    				]
    			]
    		],
    		[
    			[
    				[
    					AppConst::CTI_CALL_LIST, 
    					'list_id'
    				],
    				[
    					AppConst::CTI_UPLOADS, 
    					'list_id'
    				]
    			]
    		]
    	];
    	$params->operationType = DataBaseAccessor::FUNCTION_COUNT_DISTINCT;
    	$params->aggregateFunctionParameter = 'phone_number';
    	//$params->operationResultName = 'leads_offered';
    	
    	//var_dump($parameters['model']->dateRange);
    	//var_dump($parameters['model']->market);
    	//return ['Number of Leads Offered:' => 15000];
    }
    
    private function getMonths($model)
    {
    	$startDate = new DateTime($model->startDate);
    	$endDate = new DateTime($model->endDate);
    	var_dump($startDate);
    	/* while($date <= $en) */
    	$allMonths = ['JAN', 'FEB', 'MAR', 'APR', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
    }
    
    public function totalLeadsDialed($startDate, $endDate, $customer)
    {
    	$uploadsTable = AppConst::CTI_UPLOADS;
    	
    	$params = new QueryParameter();
    	return self::getResults($params);
    }
    
    


 /*** METHODS TO INSERT STATS ***/

    /**
     * Count of users per subscription category (Smart Shopper, VIP, MOD, only credits) (for different portal)
     * The total count of all the membership category. (for example for PP - 123456, on EU - Smart Shopper - 315, MOD - 120 and so on).
     *
     * @param int $partnerId
     * @access public
     */
    public function countUsersBySubscriptionCategory($partnerId)
    {
        $usersTable = App::$DBTABLES['users'];
        $ucTable = App::$DBTABLES['users_constraints'];
        $subscriptionsTable = App::$DBTABLES['subscr_subscription'];

        $yesterday = new DateTime;

        $yesterday->modify('-1 day');

        $params = new QueryParameter();

        $params->dateStats = $yesterday->format('Y-m-d');

        $params->baseTable = $usersTable;

        $params->jointFields = array(array(array(array($usersTable, 'users_id'),
                                                 array($ucTable, 'users_id'))),
                                     array(QueryParameter::TYPE, 'LEFT',
                                           array(array($usersTable, 'users_id'),
                                          		 array($subscriptionsTable, 'users_id'))));

        $params->filters = array(array('merchant_id', $partnerId, '='));

        $params->groupByFields = array(array($subscriptionsTable, 'dubli_package_id'),
                                       array($subscriptionsTable, 'is_active'),
                                       array($usersTable, 'users_active'));

        $params->operationType = DataBaseAccessor::FUNCTION_COUNT;

        $params->partnerId = $partnerId;

        $params->functionName = __FUNCTION__;

        $this->processParams($params);
    }

    /**
     * Stores revenue from the shopping mall.
     *
     * @param int $partnerId
     * @param string('YYYY-MM-DD') $asOfDate
     */

    public function storeMallSalesAndCommission($partnerId, $asOfDate=null){
    	if (App::$ENGINE != 'eu') return;
    	$params = new stdClass();

    	$params->groupByFields = array(array(null,'Locale'),array(null,'currency'));
    	$params->dateStats = date('Y-m-d', empty($asOfDate)?(time() - 24*60*60):strtotime($asOfDate));
    	$params->partnerId = $partnerId;
    	$params->engine = 'ALL';
    	$params->functionName = 'MallTotalSales';

    	$totalSales = array();
    	$totalCommission = array();

    	$url = App::$MALL_REST_API['host'];
    	$urlParams = array(
    			'method'=>'getSales',
    			'startDate'=>$params->dateStats,
    			'partner'=>$partnerId,
    			'User'=>App::$MALL_REST_API['User'],
    			'Pass'=>App::$MALL_REST_API['Pass']
    	);
    	$first = true;
    	foreach ($urlParams as $paramName => $paramValue){
    		$url .= $first?'?':'&';
    		$first = false;
    		$url .= $paramName.'='.$paramValue;
    	}
    	$xml = simplexml_load_file($url);

    	foreach($xml->Totals->Locale as $data){
    		$totalSales[] = array(
    				'Locale'=>$data->attributes()->locale,
    				'currency' =>$data->attributes()->currency,
    				'result'=>$data->Sales
    				);
    		$totalCommission[] = array(
    				'Locale'=>$data->attributes()->locale,
    				'currency' =>$data->attributes()->currency,
    				'result'=>$data->Commission
    				);
    	}

    	$params->results = $totalSales;

    	$this->processParams($params);

		$obj = Stats::getInstance();
    	$params->functionName = 'MallTotalCommission';
    	$params->results = $totalCommission;
		$obj->processParams($params);
    }

    /**
     * Stores count of Xpress auctions that have been paid for on a specific date
     *
     * @param INT $partnerId
     * @param sting $asOfDate
     */

    public function storePaidXpressAuctions($partnerId, $asOfDate = null){

    	if ($partnerId != App::$DEFAULT_MERCHANTID) return;

		$params = new QueryParameter();

		$params->partnerId = $partnerId;

    	$params->dateStats = date('Y-m-d', empty($asOfDate)?(time() - 24*60*60):strtotime($asOfDate));

    	$params->baseTable = App::$DBTABLES['auction__data_archiv'];

    	$params->filters = array(array(array('DATE', array('date_purchased')), $params->dateStats, '=')
    							,array('auction_type', App::AUCTION_XPRESS, '=')
    			);

    	$params->jointFields = 
    	[
    		[
    			[
    				[
    					$params->baseTable, 
    					'auction_id'
    				],
    				[
    					App::$DBTABLES['orders__products'], 
    					'auction_id'
    				]
    			]
    		],
    		[
    			[
    				[
    					App::$DBTABLES['orders__products'], 
    					'orders_id'
    				],
    				[
    					App::$DBTABLES['orders__order'], 
    					'orders_id'
    				]
    			],
    		]
    	];
    	$params->functionName = str_replace('store', '', __FUNCTION__);

    	$params->operationType = DataBaseAccessor::FUNCTION_COUNT;

    	$this->processParams($params);

    }
    
    /**
     * Xpress Discounts
     * @param string $date
     * @access public
     */
    public function xpressDiscounts($date = null)
    {
        if(isset($date))
        {
            $yesterday = new DateTime($date);
        }
        else
        {
            $yesterday = new DateTime;
            $yesterday->modify('-1 day');
        }

        $ada = App::$DBTABLES['auction__data_archiv'];
        $aoa = App::$DBTABLES['auction_offers_archiv'];

        $params = new QueryParameter();
        $params->baseTable = $ada;
        $params->additionalFields = array(array($ada , 'auction_date_end'),
                                          array($ada , 'auction_value'),
                                          array($ada , 'auction_sold_price'),
                                          array('saved', array('-', array($ada .'.auction_value', $ada .'.auction_sold_price'))),
                                          array($ada , 'auction_sold_users_id'),
                                          array($ada , 'auction_sold_engine'));
        $params->filters = array(array(array('DATE', array('auction_date_end')), $yesterday->format('Y-m-d'), '='),
                                 array('users_id', 1, '='),
                                 array('auction_type', DBColumn::AUCTION_XPRESS, '='));
        $params->jointFields = array(array(array(array($aoa , 'auction_id'),
                                                 array($ada , 'auction_id'))));
        $params->groupByFields = array(array($ada , 'auction_id'));
        $params->operationType = DataBaseAccessor::FUNCTION_SUM;
        $params->aggregateFunctionParameter = array('*', array(array('IFNULL', array('users_id', 0)), 0.2));
        $params->operationResultName = 'discount_amount';
        $params->functionName = __FUNCTION__;
        $params->repositoryName = 'stats_xpress_discounts';
        $this->processParams($params);
    }
    
    /**
     * Count users subscriptions by date, type and country.
     * @param int $partnerId
     * @param string $date
     * @access public
     */
    public function countUsersBySubscriptionTypeAndCountry($partnerId, $date = null)
    {
        $ucTable = App::$DBTABLES['users_constraints'];
        $usersTable = App::$DBTABLES['users'];
        $subscriptionsTable = App::$DBTABLES['subscr_subscription'];
        if(isset($date))
        {
            $yesterday = new DateTime($date);
        }
        else
        {
            $yesterday = new DateTime;
            $yesterday->modify('-1 day');
        }
        $params = new QueryParameter();
    
        $params->baseTable = $usersTable;
    
        $params->filters = array(array('merchant_id', $partnerId, '='));
    
        $params->jointFields = array(array(array(array($usersTable, 'users_id'),
            array($ucTable, 'users_id'))),
            array(QueryParameter::TYPE, 'LEFT',
                array(array($ucTable, 'users_id'),
                    array($subscriptionsTable, 'users_id'))));
    
        $params->groupByFields = array(array($subscriptionsTable, 'dubli_package_id'),
            array($ucTable, 'countries_id'),
            array($ucTable, 'affiliate_cpg_id'),
            array($ucTable, 'merchant_id'),
            array($subscriptionsTable, 'is_active'));
    
        $params->operationType = DataBaseAccessor::FUNCTION_COUNT;
    
        $params->operationResultName = 'user_count';
    
        $params->functionName = __FUNCTION__;
    
        $params->partnerId = $partnerId;
    
        $params->repositoryName = App::$DBTABLES['stats_user_count'];
    
        $this->processParams($params);
    }
    
    
   
    /*** END STORING METHODS ***/

    /**
     * DISPLAY METHOD
     */

    /**
     * 
     * @param QueryParameter $params
     */
    public function getReportData($params)
    {
    	$this->{$params->functionName}($params);
    	if(!isset($params->db_connection))
    	{
    		$params->db_connection = DBConnection::getByIdentifier('Icude');
    	}
    	var_dump(DataBaseAccessor::getSQL($params));
    	die;
    	$data = DataBaseAccessor::getResults($params)->fetch(PDO::FETCH_ASSOC);
    	return $data;
    }
    
    /*** PRIVATE ***/
    

    /**
     * Calculates the results of a stats method and inserts them in the database
     *
     * @param QueryParameter $params
     * @access public
     */
    private function processParams($params)
    {
        $this->set('name', $params->functionName);
        $this->setAggregateFieldName($params);
        if(!$this->exists(array(array('name', $this->name, 'LIKE'),
            array('aggregate_field_name', $this->aggregate_field_name, 'LIKE'))))
        {
            $this->insert(null, true);
        }
        $now = new DateTime;
        $params->dateCreated = $now->format('Y-m-d H:i:s');
        $yesterday = new DateTime;
        $yesterday->modify('-1 day');
        if(!isset($params->dateStats))
        {
            $params->dateStats = $yesterday->format('Y-m-d');
        }
        if(!isset($params->partnerId))
        {
            $params->partnerId = 0;
        }
        $statsInfo = StatsInfo::getInstance();
        $statsInfo->setAggregateCategory($params);
        $statsInfo->set('stats_id', $this->getId())
        ->set('date_stats', $params->dateStats)
        ->set('date_created', $params->dateCreated)
        ->set('partner_id', $params->partnerId)
        ->set('engine', APP::$ENGINE);
        $statsInfo->insert(null, true);
        if($params->repositoryName)
        {
            if(!isset($params->additionalFields))
            {
                $params->additionalFields = array();
            }
            $params->additionalFields[] = array('stats_info_id', QueryParameter::PARAM_CONSTANT, $statsInfo->getId());
        }
        /* @var  $params QueryParameter */
        if(!isset($params->operationResultName))
        {
            $params->operationResultName = 'result';
        }
        if(!isset($params->results))
        {
            $params->results = DataBaseAccessor::getResults($params);
        }
        if($params->results)
        {
            $this->processResults($params);
        }
        unset($params);
    }

    /**
     * Sets the aggregate_field_name attribute
     *
     * @param QueryParameter $params
     * @access private
     */
    private function setAggregateFieldName($params)
    {
        $this->aggregate_field_name = '';
            if(isset($params->groupByFields))
            {
                foreach($params->groupByFields as $aggregateField)
                {
                    //if $aggregateField[1] is an array, it means it's a function, in which case, the name of the result is $aggregateField[0]
                    if(is_array($aggregateField[1]))
                    {
                        $this->aggregate_field_name .= $aggregateField[0].', ';
                    }
                    else
                    {
                        $this->aggregate_field_name .= $aggregateField[1].', ';
                    }
                }
            }
            else
            {
               $this->aggregate_field_name = 'all';
            }
            $this->aggregate_field_name = trim($this->aggregate_field_name, ', ');
    }





    /**
     * Puts the results in the database
     * @param QueryParameter $params
     */
    private function processResults($params)
    {
        if($params->repositoryName)
        {
            $this->insertResultsIntoCustomTable($params);
        }
        else
        {
            $this->insertResultsIntoStatsInfo($params);
        }
    }

    /**
     *
     * @param QueryParameter $params
     * @access private
     */
    private function insertResultsIntoCustomTable($params)
    {
        /* @var $statsInfo StatsInfo */
        
        if(!in_array($params->repositoryName, App::$DBTABLES))
        {
            $table = $this->setRepository($params);
            $params->newTable = true;
        }
        else
        {
            $table = Table::getInstanceBy(array(array('table_name', $params->repositoryName, '=')));
        }
        /* @var $dbObject ConcreteDBAccessor */
        foreach($params->results as $result)
        {
            $dbObject = ConcreteDBAccessor::getInstance();
            $dbObject->reConfig($table->getDbPrimaryKey()->name, $table->get('table_name'));
            foreach($result as $attributeKey => $attributeValue)
            {
                $columnName = $attributeKey;
                if(isset($attributeValue))
                {
                    $dbObject->set($columnName, $attributeValue);
                }
                else
                {
                    $dbObject->set($columnName, 'N/A');
                }
                $filters = array(array('name', $columnName, 'LIKE')
                                       );
                $column = Column::getInstanceBy($filters);
                /* @var $column Column */
                $params->attributeValue = $attributeValue;
                $column->control($params);
            }
            $dbObject->insert(null, true);
        }
    }
    
    /**
     * Verifies if needed db structure exists and creates it if it doesn't
     * @param QueryParamenter $params
     * @return Table
     * @access private
     */
    private function setRepository($params)
    {
        /* @var $table Table */
        $table = Table::getInstance();
        $table->setColumnsFromResults($params);
        $table->set('table_name', $params->repositoryName);
        $table->assureExistence();
        return $table;
    }

    /**
     *
     * @param QueryParameter $params
     */
    private function insertResultsIntoStatsInfo($params)
    {
        /* @var $statsInfo StatsInfo */
        foreach($params->results as $resultRow)
        {
            $statsInfo = StatsInfo::getInstance();
            $statsInfo->set('engine', isset($params->engine)?$params->engine:APP::$ENGINE);
            $statsInfo->set('stats_id', $this->getId())
                      ->set('date_stats', $params->dateStats)
                      ->set('date_created', $params->dateCreated)
                      ->set('partner_id', $params->partnerId)
                      ->set('result', $resultRow['result'])
             		  ->setResultRow($resultRow);
            $statsInfo->setAggregateCategory($params);
            $statsInfo->insert(null, true);
        }
    }

    /*** END PRIVATE ***/





/*** RETRIEVING ***/

    /**
     * Finds the average active user count for a date range.
     * @param QueryParameter $params
     * @return float
     * @access public
     * @static
     */
    public static function getAverageUserCountForDates($params)
    {
        $statsInfoTable = App::$DBTABLES['stats_info'];

        $dates = '';
        foreach($params->dateArray as $date)
        {
            $dates .= "'$date', ";
        }
        $dates = trim($dates, ', ');
        $paramsActiveUserCount = new QueryParameter();
        /* @var $stats Stats */
        $stats = Stats::getInstance();

        $params->baseTable = $stats;
        $params->filters = array(array('name', 'countUsersByGender', '='),
            array('date_stats', $dates, 'IN'),
            array('partner_id', $params->partnerId, '='),
            array('engine', App::$ENGINE, '='));
        $params->jointFields = array(array(array(array($statsInfoTable, $stats->getPKey()),
            array($stats->getDbTable(), $stats->getPKey()))));
        $params->operationType = DataBaseAccessor::FUNCTION_SUM;
        $params->operationResultName = 'userCount';
        $params->aggregateFunctionParameter = 'result';
        $params->groupByFields = array(array($statsInfoTable, 'date_stats'));

        $paramsOuterQuery = new QueryParameter();
        $paramsOuterQuery->db_connection = $stats->getMySQLResource();
        $paramsOuterQuery->baseTable = array(QueryParameter::STATEMENT => DataBaseAccessor::getSQL($params),
            QueryParameter::ALIAS => 'sumsTable');
        $paramsOuterQuery->operationType = DataBaseAccessor::FUNCTION_AVG;
        $paramsOuterQuery->aggregateFunctionParameter = 'userCount';
        $paramsOuterQuery->operationResultName = 'userCountAverage';

        $userCountAverage = DataBaseAccessor::getResults($paramsOuterQuery);

        return $userCountAverage[0]['userCountAverage'];
    }
}
?>