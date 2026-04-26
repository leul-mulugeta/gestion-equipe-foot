<?php

class ParticipantDAO
{
	private PDO $pdo;
	private JoueurDAO $joueurDAO;

	public function __construct()
	{
		$this->pdo = MySQLDataSource::getInstance()->getConnection();
		$this->joueurDAO = new JoueurDAO();
	}

	public function insert(Participant $participant): ?Participant
	{
		$requete = 'INSERT INTO participant (joueur_id, rencontre_id, type_participation, poste, evaluation) 
					VALUES (:joueur_id, :rencontre_id, :type_participation, :poste, :evaluation)';

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':joueur_id', $participant->getJoueur()->getJoueurId());
		$statement->bindValue(':rencontre_id', $participant->getRencontreId());
		$statement->bindValue(':type_participation', $participant->getTypeDeParticipation()->value);
		$statement->bindValue(':poste', $participant->getPoste()->value);
		$statement->bindValue(':evaluation', $participant->getEvaluation());

		if ($statement->execute()) {
			$participant->setParticipantId($this->pdo->lastInsertId());
			return $participant;
		}

		return null;
	}

	public function selectById(int $id): ?Participant
	{
		$requete = 'SELECT * FROM participant WHERE participant_id = :participant_id';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':participant_id', $id);
		$statement->execute();

		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			$participant = new Participant(
				$row['participant_id'],
				$this->joueurDAO->selectById($row['joueur_id']),
				$row['rencontre_id'],
				TypeDeParticipation::from($row['type_participation']),
				Poste::from($row['poste']),
				$row['evaluation'] !== null ? (int) $row['evaluation'] : null
			);

			return $participant;
		}

		return null;
	}

	public function selectAllByIdRencontre(int $idRencontre): array
	{
		$requete = 'SELECT * FROM participant WHERE rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':rencontre_id', $idRencontre);
		$statement->execute();

		$participants = [];
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$participant = new Participant(
				$row['participant_id'],
				$this->joueurDAO->selectById($row['joueur_id']),
				$row['rencontre_id'],
				TypeDeParticipation::from($row['type_participation']),
				Poste::from($row['poste']),
				$row['evaluation'] !== null ? (int) $row['evaluation'] : null
			);

			$participants[] = $participant;
		}

		return $participants;
	}

	public function delete(int $id): bool
	{
		$requete = 'DELETE FROM participant WHERE participant_id = :participant_id';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':participant_id', $id);
		$statement->execute();

		return $statement->rowCount() > 0;
	}

	public function deleteAllByIdRencontre(int $idRencontre): bool
	{
		$requete = 'DELETE FROM participant WHERE rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':rencontre_id', $idRencontre);
		return $statement->execute();
	}

	public function updateEvaluation(int $idParticipant, ?int $evaluation): bool
	{
		$requete = 'UPDATE participant SET evaluation = :evaluation WHERE participant_id = :participant_id';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':evaluation', $evaluation);
		$statement->bindValue(':participant_id', $idParticipant);
		return $statement->execute();
	}

	public function getMoyenneEvaluationByIdJoueur(int $idJoueur): float
	{
		$requete = 'SELECT AVG(evaluation) as moyenne FROM participant WHERE joueur_id = :joueur_id AND evaluation > 0';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':joueur_id', $idJoueur);
		$statement->execute();
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return $row['moyenne'] ? (float)$row['moyenne'] : 0.0;
	}
}
