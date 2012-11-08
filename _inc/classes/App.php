<?php

/**
 * App config class
 *
 * @author Diego Saa <diego.saa@onlineacg.com>
 * @copyright All rights reserved, (c) ACG 2012
 * @package acg.classes
 */

final class App
{

	static public $environment;

	static public $CONF_MYSQL_RW;

	static public $CONF_MYSQL_R;

	static public $DBTABLES =
	[
			'table' 				=> 'table',
			'attribute_type' 		=> 'attribute_type',
			'field_format'			=> 'field_format',
			'lead_list_format' 		=> 'lead_list_format',
			'lead_csv_file'			=> 'lead_csv_file',
			'lead_list'				=> 'lead_list',
			'file_format'			=> 'file_format',
			'stats'					=> 'stats',
			'stats_info'			=> 'stats_info'
	];
	
	static public function setEnvironment($environment = AppConst::ENV_PRODUCTION)
	{
		self::$environment = $environment;
		self::setDBConfig($environment);
		self::setServerVars($environment);
	}

	static public function init()
	{
		require_once('PHPExcel.php');
		include($_SERVER['DOCUMENT_ROOT'].'/_inc/classes.autoload.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/_inc/functions.php');
	}

	static public function setServerVars($env = AppConst::ENV_PRODUCTION)
	{
		switch($env)
		{
			case(AppConst::ENV_STAGING):
				$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'].$env.'/';
				break;
		}
	}

	static public function setDBConfig($env)
	{
		switch($env)
		{
			case(AppConst::ENV_LOCAL):
				self::$CONF_MYSQL_RW = 
				[
					APPConst::DB_HOST => 'localhost',
					APPConst::DB_USER => 'acg',
					APPConst::DB_PASS => 'cYSuf8rU8j8RMU9j',
					APPConst::DB_NAME => 'mydb',
					APPConst::DB_DRIVER => 'mysql',
					AppConst::DB_ATTRIBUTES => 'ATTR_ERRMODE=ERRMODE_EXCEPTION',
					AppConst::DB_OPTIONS => 'PDO::MYSQL_ATTR_INIT_COMMAND=set names utf8'
					
				];
				break;
			case(AppConst::ENV_DEVELOPMENT):
				self::$CONF_MYSQL_RW = 
				[
					APPConst::DB_HOST => '',
					APPConst::DB_USER => '',
					APPConst::DB_PASS => '',
					APPConst::DB_NAME => '',
					APPConst::DB_DRIVER => 'mysql',
					AppConst::DB_ATTRIBUTES => 'ATTR_ERRMODE=ERRMODE_EXCEPTION',
					AppConst::DB_OPTIONS => 'PDO::MYSQL_ATTR_INIT_COMMAND=set names utf8'
				];
				break;
			case(AppConst::ENV_STAGING):
				self::$CONF_MYSQL_RW = 
				[
					APPConst::DB_HOST => 'localhost',
					APPConst::DB_USER => 'root',
					APPConst::DB_PASS => 'acg3301',
					APPConst::DB_NAME => 'acg',
					APPConst::DB_DRIVER => 'mysql',
					AppConst::DB_ATTRIBUTES => 'ATTR_ERRMODE=ERRMODE_EXCEPTION',
					AppConst::DB_OPTIONS => 'PDO::MYSQL_ATTR_INIT_COMMAND=set names utf8'
				];
				break;
			case(AppConst::ENV_PRODUCTION):
				self::$CONF_MYSQL_RW = 
				[
					APPConst::DB_HOST => 'localhost',
					APPConst::DB_USER => 'root',
					APPConst::DB_PASS => 'acg3301',
					APPConst::DB_NAME => 'acg',
					APPConst::DB_DRIVER => 'mysql',
					AppConst::DB_ATTRIBUTES => 'ATTR_ERRMODE=ERRMODE_EXCEPTION',
					AppConst::DB_OPTIONS => 'PDO::MYSQL_ATTR_INIT_COMMAND=set names utf8'
				];
				break;
		}
	}
}