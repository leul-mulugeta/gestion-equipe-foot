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

	public function updateEvaluationsParticipants(array $evaluations): void
	{
		$this->pdo->beginTransaction();
		try {
			foreach ($evaluations as $participantId => $evaluation) {
				$this->updateEvaluationParticipant($participantId, $evaluation);
			}
			$this->pdo->commit();
		} catch (Exception $e) {
			$this->pdo->rollBack();
			error_log('Erreur modifier evaluations participants : ' . $e->getMessage());
			throw new PDOException('Erreur lors de la modification des evaluations des participants.');
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
			error_log('Erreur sauvegarde participants : ' . $e->getMessage());
			throw new PDOException('Erreur lors de la sauvegarde des participants.');
		}
	}

	private function updateEvaluationParticipant(int $participantId, int $evaluation): void
	{
		$query = 'UPDATE participant SET evaluation = :evaluation WHERE participant_id = :participant_id';
		$statement = $this->pdo->prepare($query);
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
