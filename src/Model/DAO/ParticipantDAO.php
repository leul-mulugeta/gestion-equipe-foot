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

	public function insertParticipant(Participant $participant): void
	{
		$query = 'INSERT INTO participant (joueur_id, rencontre_id, type_participation, poste, evaluation) 
					VALUES (:joueur_id, :rencontre_id, :type_participation, :poste, :evaluation)';

		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':joueur_id', $participant->getJoueur()->getJoueurId());
		$statement->bindValue(':rencontre_id', $participant->getRencontreId());
		$statement->bindValue(':type_participation', $participant->getTypeDeParticipation()->value);
		$statement->bindValue(':poste', $participant->getPoste()->value);
		$statement->bindValue(':evaluation', $participant->getEvaluation());
		$statement->execute();
	}

	public function selectParticipantById(int $participantId): ?Participant
	{
		$query = 'SELECT * FROM participant WHERE participant_id = :participant_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':participant_id', $participantId);
		$statement->execute();

		$dbLine = $statement->fetch();
		if (!$dbLine) {
			return null;
		}

		return $this->arrayToParticipant($dbLine);
	}

	public function selectParticipantsByRencontreId(int $rencontreId): array
	{
		$query = 'SELECT * FROM participant WHERE rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':rencontre_id', $rencontreId);
		$statement->execute();

		return array_map(fn($dbLine) => $this->arrayToParticipant($dbLine), $statement->fetchAll());
	}

	public function deleteParticipant(int $participantId): bool
	{
		$query = 'DELETE FROM participant WHERE participant_id = :participant_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':participant_id', $participantId);
		$statement->execute();

		return $statement->rowCount() > 0;
	}

	public function deleteParticipantsByRencontreId(int $rencontreId): void
	{
		$query = 'DELETE FROM participant WHERE rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':rencontre_id', $rencontreId);
		$statement->execute();
	}

	public function updateEvaluationParticipant(int $participantId, int $evaluation): void
	{
		$query = 'UPDATE participant SET evaluation = :evaluation WHERE participant_id = :participant_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':evaluation', $evaluation);
		$statement->bindValue(':participant_id', $participantId);
		$statement->execute();
	}

	public function selectMoyennesEvaluationByJoueurId(int $joueurId): float
	{
		$query = 'SELECT AVG(evaluation) as moyenne FROM participant WHERE joueur_id = :joueur_id AND evaluation > 0';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':joueur_id', $joueurId);
		$statement->execute();
		$dbLine = $statement->fetch();
		return $dbLine['moyenne'] ? (float)$dbLine['moyenne'] : 0.0;
	}

	private function arrayToParticipant(array $dbLine): Participant
	{
		return new Participant(
			$dbLine['participant_id'],
			$this->joueurDAO->selectJoueurById($dbLine['joueur_id']),
			$dbLine['rencontre_id'],
			TypeDeParticipation::from($dbLine['type_participation']),
			Poste::from($dbLine['poste']),
			$dbLine['evaluation'] !== null ? (int) $dbLine['evaluation'] : null
		);
	}
}
