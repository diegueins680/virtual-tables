<?php

/**
 * MySQL Connection object
 *
 * Tricky object to have "old" framework and new processes
 * running with ->connect() / ->ping() features using new and old php object who
 * connecting diffrent to the database
 *
 * @author Jens Lojek <code@code-in-design.de>
 */

class MySQL {
    /**
     * Name of connection with Read only privileges
     * @var string
     */
    const MYSQL_R = 'MYSQL_CON_R';

    /**
     * Name of connection with Read/Write privileges
     * @var string
     */

    const MYSQL_RW = 'MYSQL_CON_RW';
    /**
     * Name of connection to global databes
     * @var string
     */

    const MYSQL_GLOBAL = 'MYSQL_CON_GLOBAL';

    /**
     * Name of connection with newsletter database
     * @var string
     */
    const MYSQL_NL = 'MYSQL_CON_NL';

    const MYSQL_RECONNECT = true;

    /**
     * MySQL Connection Resource for read-only
     * @access public
     * @staticvar resource
     */
    static public $MYSQL_CON_R = null;

    /**
     * MySQL Connection Resource for read-write
     * @access public
     * @staticvar resource
     */
    static public $MYSQL_CON_RW = null;

    /**
     * MySQL Connection Resource for global database
     * @access public
     * @staticvar resource
     */
    static public $MYSQL_CON_GLOBAL = null;

    /**
     * MySQL Connection Resource for newsletter database
     * @access public
     * @staticvar resource
     */
    static public $MYSQL_CON_NL = null;

    /**
     * Configuration
     * @access private
     * @staticvar array
     */
    static private $conf = array();

    /**
     * Constructor
     *
     * @access private
     */
	private function __construct(){/*Not allowed*/}

    /**
     * Return affected rows of the last query
     *
     * Wrapper for mysql_affected_rows()
     *
     * @access public
     * @static
     * @param string|resource $ident
     * @return int
     */
    public static function affectedRows($ident)
    {
        if(!is_resource($ident))
        {
            $ident = self::$$ident;
        }

        return mysql_affected_rows($ident);
    }


    /**
	 * Connecting to specific database
     *
     * @access public
     * @static
	 * @param string $ident
	 * @return bool
	 * @throws Exception
	 */
	static public function connect( $ident )
	{
        $con = @mysql_connect(self::$conf[$ident]['host'], self::$conf[$ident]['user'], self::$conf[$ident]['pass'], true);

	    if( !$con )
	    {
            throw new Exception("[".date('Y-m-d H:i:s')."] Cant establish database connection \n" .
                "Database host: " . self::$conf[$ident]['host'] . " Request:" . $_SERVER['REQUEST_URI'] . "\n" .
                mysql_error()
            );
	    }

        if( !mysql_select_db(self::$conf[$ident]['dbname'], $con) )
        {
            throw new Exception('Database '.self::$conf[$ident]['dbname'].' not existing!');
        }

        mysql_query("SET CHARACTER SET 'utf8'",$con);
        mysql_query("SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'",$con);

        self::$$ident = $con;

        // For old Constant usage way
        if( !defined($ident) )
        {
            define($ident, $con);
        }

        return true;
	}

	/**
	 * Closing database connection
     *
     * @access public
     * @static
	 * @param string $ident
	 * @return void
	 */
    static public function close( $ident )
    {
        $ref =& self::$$ident;

        try{
            mysql_close( constant($ident) );
            mysql_close($ref);
        }catch(Exception $e){}
    }

    /**
     * Fetching the data as associative array or array of associative arrays
     *
     * Wrapper for mysql_fetch_assoc()
     *
     * @access public
     * @static
     * @param string $query
     * @param string|resource $ident
     * @return array
     */
    public static function fetchAssoc($query, $ident)
    {
        $res = self::query($query, $ident);

        return self::fetch($res, 'assoc');
    }

    /**
     * Fetch data of one cell
     *
     * Wrapper for mysql_result()
     *
     * @access public
     * @static
     * @param string $query
     * @param string|resource $ident
     * @param int $row  Def:0
     * @param int $field    Def:0
     * @return mixed
     */
    public static function fetchResult($query, $ident, $row = 0, $field = 0)
    {
        $res = self::query($query, $ident);

        return mysql_result($res, $row, $field);
    }

    /**
     * Fetching the data as object or array of objects
     *
     * Wrapper for mysql_fetch_object()
     *
     * @access public
     * @static
     * @param string $query
     * @param string|resource $ident
     * @return array
     */
    public static function fetchObject($query, $ident)
    {
        $res = self::query($query, $ident);

        return self::fetch($res, 'object');
    }

	/**
	 * Initialize a database connection for given DB Identifier
     *
     * @access public
     * @static
	 * @param string $ident
	 * @return bool
	 */
	static public function init( $ident )
	{
        if( empty(self::$conf) ) self::setConf();
        return self::connect($ident);
	}

    /**
     * Return the last inserted ID in the database
     *
     * Wrapper for mysql_insert_id()
     *
     * @access public
     * @static
     * @param string|resource $ident
     * @return int
     */
    public static function getLastInsertID($ident)
    {
        if(!is_resource($ident))
        {
            $ident = self::$$ident;
        }

        return mysql_insert_id($ident);
    }

    /**
     * Get number of rows in result
     *
     * Wrapper for mysql_num_rows()
     *
     * @access public
     * @static
     * @param string $query
     * @param string|resource $ident
     * @return <type>
     */
    public static function numRows($query, $ident)
    {
        $res = self::query($query, $ident);

        return mysql_num_rows($res);
    }

    /**
     * Perform a ping to database, if ping fails it try to reconnect(optional).
     *
     * @access public
     * @static
     * @param string $ident
     * @return bool
     */
	static public function ping( $ident )
	{
	    $ref =& self::$$ident;

	    if( !mysql_ping($ref) )
	    {
	        //log2mysql(__METHOD__, 'warning', 'PING DB Error: ' . $ident . '[' . mysql_error($ref) . ']');
            // false, reconnect if activated
            if( !self::MYSQL_RECONNECT )
            {
                return false;
            }
            else
            {
                return self::init($ident);
            }
	    }
	    else
	    {
// 	        log2mysql(__METHOD__, 'debug', 'PING DB: Our connection is ok!');
	        return true;
	    }
	}

    /**
     * Starting a mysql query
     *
     * @access public
     * @static
     * @param string $query
     * @param resource $conn Mysql Connection Resource
     * @return resource|bool
     */
    public static function query($query, $ident)
    {
        $conn = $ident;
        if(!is_resource($conn))
        {
            $conn =& self::$$ident;
        }

        if(is_resource($conn))
        {
            return mysql_query($query, $conn);
        }
        else
        {
            return false;
        }
    }

    /**
     * Fetching the data with the given type from the resource
     *
     * @access protected
     * @static
     * @param resource $resource
     * @param string $type
     * @return array
     */
    protected static function fetch($resource, $type = 'assoc')
    {
        if($resource !== false)
        {
            $function = 'mysql_fetch_' . $type;

            $return = array();
            while(($temp = $function($resource))!= false)
            {
                $return[] = $temp;
            }

            $count = count($return);

            if( $count == 0 && $type == 'object' )
            {
                return false;
            }

            return $return;
        }
        else
        {
            return false;
        }
    }

	/**
	 * Set the external $config array data available to the object
     *
     * @access private
     * @static
	 * @return void
	 */
	static private function setConf()
	{
	    global $db_server_rw, $db_username_rw, $db_password_rw, $db_name_rw;
        global $db_server_r, $db_username_r, $db_password_r, $db_name_r;
        global $dbG_server, $dbG_username, $dbG_password, $dbG_name;
        global $config;

        self::$conf = array(
            self::MYSQL_R => array(
                'host' => &$db_server_r,
                'user' => &$db_username_r,
                'pass' => &$db_password_r,
                'dbname' => &$db_name_r,
            ),
            self::MYSQL_RW => array(
                'host' => &$db_server_rw,
                'user' => &$db_username_rw,
                'pass' => &$db_password_rw,
                'dbname' => &$db_name_rw,
            ),
            self::MYSQL_GLOBAL => array(
                'host' => &$dbG_server,
                'user' => &$dbG_username,
                'pass' => &$dbG_password,
                'dbname' => &$dbG_name,
            ),
            self::MYSQL_NL => array(
                'host' => $config['databases']['newsletter']['host'],
                'user' => $config['databases']['newsletter']['dbuser'],
                'pass' => $config['databases']['newsletter']['dbpass'],
                'dbname' => $config['databases']['newsletter']['dbname'],
            ),
        );
	}
}
