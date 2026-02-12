<?php

class MySQLDataSource
{
	private PDO $pdo;
	private static ?MySQLDataSource $instance = null;

	private function __construct()
	{
		$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
		$this->pdo = new PDO($dsn, DB_USER, DB_PASS);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public static function getInstance(): MySQLDataSource
	{
		if (self::$instance === null) {
			self::$instance = new MySQLDataSource();
		}
		return self::$instance;
	}

	public function getConnection(): PDO
	{
		return $this->pdo;
	}
}
