<?php

class ParticipantDAO
{
	private static ?ParticipantDAO $instance = null;
	private readonly PDO $pdo;
	private readonly JoueurDAO $joueurDAO;

	private function __construct()
	{
		$this->pdo = DBConnection::getInstance()->getConnection();
		$this->joueurDAO = JoueurDAO::getInstance();
	}

	public static function getInstance(): ParticipantDAO
	{
		if (self::$instance === null) {
			self::$instance = new ParticipantDAO();
		}
		return self::$instance;
	}

	public function selectMoyennesEvaluationByJoueur(): array
	{
		$requete = "SELECT joueur_id, AVG(evaluation) as moyenne FROM participant GROUP BY joueur_id HAVING moyenne > 0";
		$statement = $this->pdo->prepare($requete);
		$statement->execute();
		$rows = $statement->fetchAll();

		return array_column($rows, 'moyenne', 'joueur_id');
	}
}
