<?php
$GLOBALS['VARDUMP'][] = __FILE__;
$myAutoloader = function ($classname )
{
	try
	{
		if( !preg_match('=^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$=m', $classname) )
		{
			throw new Exception('Classname ' . $classname . ' is invalid');
		}
		// 	1.
		$classPath = $_SERVER['DOCUMENT_ROOT'].'/_inc/classes/' . $classname . '.php';
		if( !file_exists($classPath) )
		{
			// 	2.
			$classPath = $_SERVER['DOCUMENT_ROOT'] . '_inc/classes/' . strtolower($classname) . '.php';
		}
			
		if( !file_exists($classPath) )
		{
			// 	3.
			$classPath = $_SERVER['DOCUMENT_ROOT'].'/yii/framework/' . strtolower($classname) . '.php';
		}

		if( !file_exists($classPath) )
		{
			throw new Exception('"' . $classPath . '" not found');
		}

		// 	Load sourcefile
		require_once( $classPath );

		// 	is class defined?
		if( !class_exists($classname) )
		{
			throw new Exception("File '$classPath' contains no class '$classname'");
		}
		return true;

	}
	catch ( Exception $e )
	{
		if( !defined('MYSQL_CON_RW') )
		{
			@error_log($e->getMessage(), 0);
		}
		else
		{
		//	@log2mysql(__FILE__, 'error', $e->getMessage());
		}
		return false;
	}
};
spl_autoload_register($myAutoloader);

$yii = $_SERVER['DOCUMENT_ROOT'].'/yii/framework/yii.php';
require_once($yii);
//YiiBase::registerAutoloader($myAutoloader, true);
?>