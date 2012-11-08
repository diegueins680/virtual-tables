<?php
class DBConnection extends ObjectFactory
{
	private static $links = [];
	
	private $resource;
	
	private $dbConnectionIdentifier;
	
	public function getDbConnectionIdentifier()
	{
		return $this->dbConnectionIdentifier;
	}
	
	public function setDbConnectionIdentifier($identifier)
	{
		$this->dbConnectionIdentifier = $identifier;
	}
	
	/**
	 * Gets the PDO resource
	 * @access public
	 * @return PDO
	 */
	public function getResource()
	{
		if(isset($this->resource))
		{
			$return = $this->resource;
		}
		else
		{
			if(isset($this->dbConnectionIdentifier))
			{
				if(isset(self::$links[$this->dbConnectionIdentifier]))
				{
					$return = self::getLink($this->dbConnectionIdentifier);
				}
			}
			else
			{
				var_dump(debug_backtrace(true));
				var_dump($this);
				echo "Error: the database connection identifier is not set!";
				die;
			}
		}
		return $this->resource;
	}
	
	public function setResource($resource)
	{
		$this->resource = $resource;
	}
	
	/**
	 * Sets the objects db connection
	 * @param string $identifier
	 */
	public function setConnection($identifier = AppConst::MYSQL_RW)
	{
		if(isset(self::$links[$identifier]))
		{
			$return = self::$links[$identifier];
		}
		else
		{
			if(!isset($this->resource))
			{
				$this->resource = self::getLink($identifier);
			}
			$return = $this;
		}
		$return->setDbConnectionIdentifier($identifier);
		return $return;
	}
	
	public static function getByIdentifier($identifier = AppConst::MYSQL_RW)
	{
		if (!isset(self::$links[$identifier] ))
		{
			$link = self::getLink($identifier);
		}
		return self::$links[$identifier];
	}

	/**
	 * 
	 * @param DBConfiguration $dbConfig
	 * @return multitype:
	 */
	public static function getLink($identifier = 'MYSQL_RW')
	{
		if (isset(self::$links[$identifier]))
		{
			return self::$links[$identifier]->resource;
			/* @var $test DBConnection */
			//$test->
		}
		else
		{
			if($identifier==AppConst::MYSQL_RW)
			{
				$dbConfig = DbConnectionConfig::getInstance();
				$conf = App::$CONF_MYSQL_RW;
				$dbConfig->set('connection_identifier', $identifier)
				->set('db_connection_host', $conf[AppConst::DB_HOST])
				->set('db_connection_user', $conf[AppConst::DB_USER])
				->set('db_connection_pass', $conf[AppConst::DB_PASS])
				->set('db_connection_db_name', $conf[AppConst::DB_NAME])
				->set('db_driver', $conf[AppConst::DB_DRIVER])
				->set('db_attributes', $conf[AppConst::DB_ATTRIBUTES])
				->set('db_options', $conf[AppConst::DB_OPTIONS]);
			}
			else
			{
				$filters =
				[
					[
						'connection_identifier',
						$identifier,
						'='
					]
				];
				//echo "filters in dbConnection: ";
				//var_dump($filters);
				$dbConfig = DBConnectionConfig::readListBy($filters)[0];
			}
			/* @var $instance DBConnection */
			$instance = DBConnection::getInstance();
			$instance->setDbConnectionIdentifier($identifier);
			$options = 
			[
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_STATEMENT_CLASS => array('MyPDOStatement', array()),
					PDO::ATTR_EMULATE_PREPARES => false,
					$dbConfig->db_options
			];
			self::$links[$identifier] = $instance;
			try
			{
				/* @var $instance DBConnection */
				$instance->setResource(new PDO ( $dbConfig->db_driver.':dbname='.$dbConfig->db_connection_db_name.';host='.$dbConfig->db_connection_host, $dbConfig->db_connection_user, $dbConfig->db_connection_pass, $options));
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
			foreach ( explode(',', $dbConfig->db_attributes) as $attribute )
			{
				$a = explode('=', $attribute);
				self::$links[$identifier]->getResource()->setAttribute(constant("PDO::{$a[0]}"), constant ( "PDO::{$a[1]}" ) ) ;
			}
		}
		return self :: $links[$identifier]->getResource();
	}

	/*
	public static function __callStatic ( $name, $args ) {
		$callback = array ( self :: getLink ( ), $name ) ;
		return call_user_func_array ( $callback , $args ) ;
	}*/
	
	/**
	 * Connecting to specific database
	 *
	 * @access public
	 * @static
	 * @param string $ident
	 * @return bool
	 * @throws Exception
	 */
	public function connect($ident)
	{
		if($ident==AppConst::MYSQL_RW)
		{
			$this->setConnectionWithObject($this);
		}
		else
		{
			$this->resource = self::getLink($ident);
		}
		/*
		$configVarName = 'CONF_'.$ident;
		$dbConfig = App::$$configVarName;
		$this->setDbConnectionConfig($dbConfig);
		$con = mysql_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], true);
		if( !$con )
		{
			throw new Exception("[".date('Y-m-d H:i:s')."] Cant establish database connection \n" .
					"Database host: " . $dbConfig['host'] . " Request:" . $_SERVER['REQUEST_URI'] . "\n" .
					mysql_error()
			);
		}
	
		if(!mysql_select_db($dbConfig['dbname'], $con))
		{
			throw new Exception('Database '.$dbConfig[$ident]['dbname'].' not existing!');
		}
	
		self::$$ident = $con;
	
		// For old Constant usage way
		if( !defined($ident) )
		{
			define($ident, $con);
		}
	*/
		return true;
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
	public function fetchAssoc($query)
	{
		$result = $this->getResource()->query($query);
		return $result->fetch(PDO::FETCH_ASSOC);
	}
	
	public function query($query)
	{
		return $this->getResource()->query($query);
	}
	
	/**
	 * Starting a mysql query
	 *
	 * @access public
	 * @static
	 * @param string $query
	 * @return resource|bool
	 */
	public function executeQuery($query)
	{
		return $this->getResource()->exec($query);
	}
	
	/**
	 * Starting a mysql query
	 *
	 * @access public
	 * @static
	 * @param string $query
	 * @return resource|bool
	 */
	public function executePreparedQuery($query, $params)
	{
		//var_dump($params);
		//var_dump($query);
		$db_connection = $this->getResource();
		try 
		{
			$sth = $db_connection->prepare($query);
		}
		catch(PDOException $err) 
		{
			// Catch Expcetions from the above code for our Exception Handling
			$trace = '<table border="0">';
			foreach ($err->getTrace() as $a => $b) {
				foreach ($b as $c => $d) {
					if ($c == 'args') {
						foreach ($d as $e => $f) {
							$trace .= '<tr><td><b>' . var_dump($a) . 
							'#</b></td><td align="right"><u>args:</u></td> <td><u>' . var_dump($e) .
							 '</u>:</td><td><i>' . var_dump($f) . '</i></td></tr>';
						}
					} else {
						$trace .= '<tr><td><b>' . var_dump($a) . '#</b></td><td align="right"><u>' . 
						var_dump($c) . '</u>:</td><td></td><td><i>' . 
						var_dump($d) . '</i></td>';
					}
				}
			}
			$trace .= '</table>';
			echo '<br /><br /><br /><font face="Verdana"><center><fieldset style="width: 66%; border: 4px solid white; background: black;"><legend><b>[</b>PHP PDO Error ' . strval($err->getCode()) . '<b>]</b></legend> <table border="0"><tr><td align="right"><b><u>Message:</u></b></td><td><i>' . $err->getMessage() . '</i></td></tr><tr><td align="right"><b><u>Code:</u></b></td><td><i>' . strval($err->getCode()) . '</i></td></tr><tr><td align="right"><b><u>File:</u></b></td><td><i>' . $err->getFile() . '</i></td></tr><tr><td align="right"><b><u>Line:</u></b></td><td><i>' . strval($err->getLine()) . '</i></td></tr><tr><td align="right"><b><u>Trace:</u></b></td><td><br /><br />' . $trace . '</td></tr></table></fieldset></center></font>';
			var_dump($err);
			die;
		}
		
		//echo $sth->queryString;
		//echo '<br><br>';
		// output the query and the query with values inserted
		try 
		{
			$sth->execute($params);
		} 
		catch (PDOException $err) 
		{
			// Catch Expcetions from the above code for our Exception Handling
			$trace = '<table border="0">';
			foreach ($err->getTrace() as $a => $b) {
				foreach ($b as $c => $d) {
					if ($c == 'args') {
						foreach ($d as $e => $f) {
							$trace .= '<tr><td><b>' . strval($a) . '#</b></td><td align="right"><u>args:</u></td> <td><u>' . $e . '</u>:</td><td><i>' . $f . '</i></td></tr>';
						}
					} else {
						$trace .= '<tr><td><b>' . strval($a) . '#</b></td><td align="right"><u>' . $c . '</u>:</td><td></td><td><i>' . $d . '</i></td>';
					}
				}
			}
			$trace .= '</table>';
			echo '<br /><br /><br /><font face="Verdana"><center><fieldset style="width: 66%; border: 4px solid white; background: black;"><legend><b>[</b>PHP PDO Error ' . strval($err->getCode()) . '<b>]</b></legend> <table border="0"><tr><td align="right"><b><u>Message:</u></b></td><td><i>' . $err->getMessage() . '</i></td></tr><tr><td align="right"><b><u>Code:</u></b></td><td><i>' . strval($err->getCode()) . '</i></td></tr><tr><td align="right"><b><u>File:</u></b></td><td><i>' . $err->getFile() . '</i></td></tr><tr><td align="right"><b><u>Line:</u></b></td><td><i>' . strval($err->getLine()) . '</i></td></tr><tr><td align="right"><b><u>Trace:</u></b></td><td><br /><br />' . $trace . '</td></tr></table></fieldset></center></font>';
			var_dump($err);
		}
		//echo $sth->_debugQuery();
		//echo '<br><br>';
		return $sth;
	}
	
	

	/**
	 * @var Name of the variable where we store the object's connection;
	 */
	const CONNECTION_VAR_NAME = 'dbConnectionVarName';

	/**
	 * @var Name of the variable where we store the connection's configuration
	 */
	const CONFIG_VAR_NAME = 'config';

	/**
	 * @var Name of the variable where we store the object's connection name;
	 */
	const CONNECTION_IDENT_VAR_NAME = 'connection_identifier';

	const MYSQL = 'MySQL';

	const ACCESS = 'Access';

	/**
	 *
	 */
	protected function setDbConnectionConfig($config)
	{
		foreach($config as $configAttrib => $configValue)
		{
			$this->$configAttrib = $configValue;
		}
	}
	
	/**
	 * 
	 * @param string $ident
	 */
	public function getInstanceFromDB($ident)
	{
		return self::getInstanceBy
		(
				[
					[
						self::CONNECTION_IDENT_VAR_NAME, $ident, '='
					]
				]
		);
	}
	
	public static function getConnectionOfType($type = MySQL::MYSQL, $ident = AppConst::MYSQL_RW)
	{
		return $type::getInstance
		(
				null,
				null,
				[
					ObjectFactory::ATTRIBUTES =>
					[
						DBConnection::CONNECTION_IDENT_VAR_NAME => $ident
					]
				]
		);
	}
}
?>