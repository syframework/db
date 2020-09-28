<?php
namespace Sy\Db;

class PDOManager {

	private static $connections = array();

	public static function getPDOInstance($dsn, $username = '', $passwd = '', array $options = array()) {
		$key = md5($dsn . $username . $passwd . serialize($options));
		if (!isset(self::$connections[$key])) {
			$pdo = new \PDO($dsn, $username, $passwd, $options);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			self::$connections[$key] = $pdo;
		}
		return self::$connections[$key];
	}

}