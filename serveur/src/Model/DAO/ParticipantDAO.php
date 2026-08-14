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

	public function joueurAParticipe(int $joueurId): bool
	{
		$query = 'SELECT 1 FROM participant WHERE joueur_id = :joueur_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':joueur_id', $joueurId);
		$statement->execute();

		return $statement->fetchColumn() !== false;
	}

	public function selectMoyennesEvaluationByJoueur(): array
	{
		$requete = "SELECT joueur_id, AVG(evaluation) as moyenne FROM participant GROUP BY joueur_id HAVING moyenne > 0";
		$statement = $this->pdo->prepare($requete);
		$statement->execute();
		$rows = $statement->fetchAll();

		return array_column($rows, 'moyenne', 'joueur_id');
	}

	public function selectParticipantsByRencontreId(int $rencontreId): array
	{
		$query = 'SELECT p.participant_id, p.rencontre_id, p.type_participation, p.poste, p.evaluation, j.*
				  FROM participant AS p
				  JOIN joueur AS j ON j.joueur_id = p.joueur_id
				  WHERE p.rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':rencontre_id', $rencontreId);
		$statement->execute();

		return array_map(fn($dbLine) => $this->arrayToParticipant($dbLine), $statement->fetchAll());
	}

	public function updateEvaluationsParticipants(int $rencontreId, array $evaluationsData): void
	{
		$this->pdo->beginTransaction();
		try {
			foreach ($evaluationsData as $evaluationData) {
				$this->updateEvaluationParticipant($rencontreId, $evaluationData['participantId'], $evaluationData['evaluation']);
			}
			$this->pdo->commit();
		} catch (Throwable $e) {
			$this->pdo->rollBack();
			throw $e;
		}
	}

	public function sauvegarderParticipants(int $rencontreId, array $participants): void
	{
		$this->pdo->beginTransaction();
		try {
			$this->deleteParticipantsByRencontreId($rencontreId);
			foreach ($participants as $participant) {
				$this->insertParticipant($participant);
			}
			$this->pdo->commit();
		} catch (Throwable $e) {
			$this->pdo->rollBack();
			throw $e;
		}
	}

	private function updateEvaluationParticipant(int $rencontreId, int $participantId, int $evaluation): void
	{
		$query = 'UPDATE participant SET evaluation = :evaluation WHERE rencontre_id = :rencontre_id AND participant_id = :participant_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':rencontre_id', $rencontreId);
		$statement->bindValue(':evaluation', $evaluation);
		$statement->bindValue(':participant_id', $participantId);
		$statement->execute();
	}

	private function deleteParticipantsByRencontreId(int $rencontreId): void
	{
		$query = 'DELETE FROM participant WHERE rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':rencontre_id', $rencontreId);
		$statement->execute();
	}

	private function insertParticipant(Participant $participant): void
	{
		$query = 'INSERT INTO participant (joueur_id, rencontre_id, type_participation, poste)
					VALUES (:joueur_id, :rencontre_id, :type_participation, :poste)';

		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':joueur_id', $participant->getJoueur()->getJoueurId());
		$statement->bindValue(':rencontre_id', $participant->getRencontreId());
		$statement->bindValue(':type_participation', $participant->getTypeDeParticipation()->value);
		$statement->bindValue(':poste', $participant->getPoste()->value);
		$statement->execute();
	}

	private function arrayToParticipant(array $dbLine): Participant
	{
		return new Participant(
			$dbLine['participant_id'],
			$this->joueurDAO->arrayToJoueur($dbLine),
			$dbLine['rencontre_id'],
			TypeDeParticipation::from($dbLine['type_participation']),
			Poste::from($dbLine['poste']),
			$dbLine['evaluation'] !== null ? (int) $dbLine['evaluation'] : null
		);
	}
}
