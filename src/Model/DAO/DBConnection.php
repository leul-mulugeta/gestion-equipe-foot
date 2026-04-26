<?php

class DBConnection
{
	private PDO $pdo;
	private static ?DBConnection $instance = null;

	private function __construct()
	{
		$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
		$this->pdo = new PDO($dsn, DB_USER, DB_PASS);
		
		// On configure PDO pour lancer des exceptions en cas d'erreur SQL
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// On force le mode de fetch par défaut en tableau associatif
		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	}

	public static function getInstance(): DBConnection
	{
		if (self::$instance === null) {
			self::$instance = new DBConnection();
		}
		return self::$instance;
	}

	public function getConnection(): PDO
	{
		return $this->pdo;
	}
}
