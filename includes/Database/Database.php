<?php
class DatabaseConnectionFactory
{
	static protected $connection = null;

	public static function getConnection()
	{
		if ( self::$connection )
			return self::$connection;
		else
			return self::$connection = new mysqli('localhost','root','','ctec227_final_project');
	}
}

class DatabaseFactory
{
	protected static $lastError = null;

	public static function getLastError() {
		return self::$lastError;
	}
}
