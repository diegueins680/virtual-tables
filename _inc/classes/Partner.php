<?php

/**
 * Partner - DubLi Partner Program - merchant account
 *
 * Note:
 * Any changes to the database made by using this object, will be overwritten as soon something changed in
 * the global database and got syncronized to each portal!
 * Use this object as a read-only object only!!!
 *
 * @author Jens Lojek <code@code-in-design.de>
 * @copyright All rights reserved, (c) DubLi.com 2011
 * @package dubli.classes
 * @since 2011-10-18
 *
 * @uses App
 * @uses Utils
 * @uses MySQL
 * @uses PartnerProfile
 * @uses PartnerSettings
 */

class Partner
{

    /**
     * Counter for found rows in readListBy()
     * @staticvar int
     */
    public static $foundRows = null;

    /**
     * Whether or not we using the Global Database or local database.
     * @var bool
     */
    static public $useGlobalDB = false;

    /**
     * Name of primary key in db table
     * @var STRING
     */
    protected $PKey = 'partner_id';

    /**
     * The Id
     * @var INTEGER
     */
    protected $Id = null;

    /**
     * Storing database data
     * @var ARRAY
     */
    protected $data = array();

    /**
     * data modified since reading?
     * @var BOOLEAN
     */
    protected $isModified = false;

    /**
     * reference to part of global $config array
     * @var ARRAY
     */
    private $c;

    /**
     * Object of PartnerProfile
     * @var PartnerProfile
     */
    private $PartnerProfile;

    /**
     * Object of PartnerSettings
     * @var PartnerSettings
     */
    private $PartnerSettings;

    /**
     * Wrapped accessible public property to access PartnerProfile properties.
     *
     * @var PartnerProfile
     */
    public $profile;

    /**
     * Wrapped accessible public property to access PartnerSettings properties.
     *
     * @var PartnerSettings
     */
    public $settings;

    /**
     * Instances
     * @staticvar Partner
     */
    private static $instances;

    /**
     * MySQL ressource
     * @var ressource
     */
    private $mysql_res;


    /**
     * Constructor
     *
     * @access private
     * @param int $Id
     */
    private function __construct( $Id=null )
    {
        $this->_setConfig();

        if( !is_null($Id) ) {
            $this->Id = (int)$Id;
            $this->read();
        }
    }

    /**
     * The singleton method
     * @access public
     * @static
     * @param int $Id
     * @return Partner
     */
    static public function getInstance( $Id=null )
    {
        $Id = trim($Id);
        if( empty($Id) ) {
            $Id = null;
            $idx = uniqid('I', true);
        } else {
            $Id = $idx = (int)$Id;
        }

        if( !isset(self::$instances[$idx]) || (!self::$instances[$idx] instanceof self) ) {
            self::$instances[$idx] = new self($Id);
        }

        return self::$instances[$idx];
    }

    /**
     * Returns Instance of Partner for passed filters
     *
     * @param array $filters
     * @access static
     * @return Partner
     */
    static public function getInstanceBy( array $filters )
    {
        $obj = new self;

        return $obj->read($filters);
    }

    /**
     * Find all existing partners for passed filters
     *
     * @param array $filters
     * @param string $orderby   Optional, SQL Order by string
     * @param int $limit    MySQL Limit to # results
     * @param int $offset   MySQL Offset for Limit
     * @access public
     * @static
     * @return array
     */
    static public function readListBy( $filters = null, $orderBy='', $limit=0, $offset=0 )
    {
        $orderBy = Utils_Format::prepareInput($orderBy);
        $limit = (int)$limit;
        $offset = (int)$offset;
        $results = array();

        $obj = new self;

        if( empty($filters) ) {
            $filters = array( array('1', 1, '=') ); // get all
        }

        $sqlLimit = ($limit > 0) ? ' LIMIT ' . $offset . ',' . $limit : '';

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `".App::$DBTABLES['partner']."` WHERE ";
        $sql .= Utils::sqlPrepareWhere($filters);
        if( !empty($orderBy) )
        {
            $sql .= " ORDER BY " . $orderBy;
        }
        $sql .= $sqlLimit;

        $res = MySQL::fetchAssoc($sql, MySQL::$MYSQL_CON_RW);
        if( !$res ) return $results;

        // readout found rows
        self::getFoundRows(MySQL::$MYSQL_CON_RW);

        foreach( $res as $row )
        {
            $self = new self( (int)$row[$obj->PKey] );
            foreach( $row as $k => $v )
            {
                $self->set($k, $v);
            }
            $results[] = $self;
        }

        return $results;
    }

    /**
     * Readout the found rows for the readListBy-Method and save them in the
     * self::$foundRows var
     *
     * @param ressource $ressource  MySQL ressource link
     * @access protected
     * @static
     * @return void
     */
    static protected function getFoundRows( $ressource )
    {
        $stmt = "SELECT FOUND_ROWS()";

        $count = (int)MySQL::fetchResult($stmt, $ressource);

        self::$foundRows = $count;
    }

    /**
     * Returns Object primary key id
     * @return int
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Check if this Partner is active
     *
     * @access public
     * @return bool
     */
    public function isActive()
    {
        return ( (bool)$this->is_active );
    }

    /**
     * Activate/Deactivate Partner
     *
     * @access public
     * @return Partner
     */
    public function setActive( $status=true )
    {
        $this->set('is_active', (bool)$status)->update();

        //log2mysql(__METHOD__, 'info', 'Partner account ' . $this->getId() . ' ' . (true==$this->get('is_active')?'activated':'deactivated'));

        return $this;
    }

    /**
     * Read single data record from database
     * @param $filters ARRAY (opt.)
     * @return Partner
     */
    public function read( $filters=null )
    {
        if( empty($filters) ) {
            $filters = array( array($this->PKey, $this->Id, '=') );
        }

        $sql = "SELECT * FROM `".App::$DBTABLES['partner']."` WHERE ";
        $sql .= Utils::sqlPrepareWhere($filters, array($this->PKey));
        $sql .= " LIMIT 1";

        $this->data = array();

        $res = MySQL::fetchAssoc($sql, $this->mysql_res);

        if( !$res || empty($res[0])) {
            return $this;
        }

        foreach($res[0] as $key => $value) {
            $this->set($key, $value);
        }

        $this->isModified = false;

        $this->profile = $this->getPartnerProfile();
        $this->settings = $this->getPartnerSettings();

        return $this;
    }

    /**
     * Insert new record into database.
     * When insert was successfull it returns a instance of inserted data, when
     * it failed, it throws UbException
     * @throws UbException
     * @return Partner
     */
    public function insert()
    {
        if( !$this->isModified ) return $this;

        $stmt = "INSERT INTO `".App::$DBTABLES['partner']."` SET ";
        $stmt .= Utils::sqlPrepareSet($this->data);

        if( !MySQL::query($stmt, $this->mysql_res) ) {
            throw new UbException( mysql_error($this->mysql_res) . "\n" . $stmt, mysql_errno($this->mysql_res));
        }

        $newId = MySQL::getLastInsertID($this->mysql_res);

        $this->Id = (int)$newId;

        return ($this->read());
    }

    /**
     * Update data to database
     * @return BOOLEAN
     */
    public function update()
    {
        if( !$this->isModified ) return true;
        if( empty($this->Id) ) return false;

        $sql = "UPDATE `".App::$DBTABLES['partner']."` SET ";
        $sql .= Utils::sqlPrepareSet($this->data, array($this->PKey));
        $sql .= " WHERE `".$this->PKey."` = " . (int)$this->Id;

        return MySQL::query($sql, $this->mysql_res);
    }

    /**
     * Shortcut method which will determine whether a row
     * with the current instances properties exists. If so, it will
     * preload those values (side effects).
     *
     * @return boolean
     */
    public function exists()
    {
        if( empty($this->data) ) $this->read();

        $exists = !empty($this->data);

        return $exists;
    }

    /**
     * Get property from object
     * @param STRING $name
     * @return MIXED
     */
    public function get($name)
    {
        switch($name)
        {
            default:
                return $this->$name;
            break;
        }
    }

    /**
     * Set property value
     * @param STRING $name
     * @param MIXED $value
     * @return Partner
     */
    public function set($name, $value)
    {
        if( isset($this->$name) && $this->$name != $value ) {
            $this->isModified = true;
        }

        if( $name == $this->PKey ) $this->Id = (int)$value;

        $this->$name = $value;

        return $this;
    }

    /**
     * Check if property of $varName is empty or not
     * @param string $varName
     * @return bool
     */
    public function isEmpty( $varName )
    {
        $var = $this->$varName;
        return (empty($var));
    }

    /**
     * override magic getter
     * @param STRING $name
     * @return MIXED
     */
    public function __get($name)
    {
        if( array_key_exists($name, $this->data) ) {
            switch($name)
            {
                case 'is_active':
                    return (bool)$this->data[$name];
                    break;
                default:
                    return $this->data[$name];
                    break;
            }
        }

        return null;
    }

    /**
     * override magic setter
     * @param STRING $name
     * @param MIXED $value
     * @return Partner
     */
    public function __set($name, $value)
    {
        switch( $name )
        {
            case 'partner_id':
            case 'pp_account_id':
            case 'is_active':
                $value = (int)$value;
            break;
            case 'date_last_modified':
            case 'date_created':
                if( is_int($value) ) $value = date('Y-m-d H:i:s', $value);
            break;
        }

        if( !isset($this->data[$name]) || $this->data[$name] != $value ) {
            $this->isModified = true;
        }

        $this->data[$name] = $value;

        return $this;
    }

    /**
     * @param string $varName
     * @return bool
     */
    public function __isset( $varName )
    {
        return (isset($this->$varName) || isset($this->data[$varName]) );
    }


    /*
     * PRIVATES
     */


    private function _setConfig()
    {
        if( is_null(MySQL::$MYSQL_CON_RW) ) MySQL::init('MYSQL_CON_RW');
        $this->mysql_res = MySQL::$MYSQL_CON_RW;

        if( self::$useGlobalDB === true ) {
            if( is_null(MySQL::$MYSQL_CON_GLOBAL) ) MySQL::init('MYSQL_CON_GLOBAL');
            $this->mysql_res = MySQL::$MYSQL_CON_GLOBAL;
        }
    }

}
