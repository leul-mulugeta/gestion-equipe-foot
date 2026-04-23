<?php

class ParticipantDAO
{
	private PDO $pdo;
	private JoueurDAO $joueurDAO;
	private RencontreDAO $rencontreDAO;

	public function __construct()
	{
		$this->pdo = MySQLDataSource::getInstance()->getConnection();
		$this->joueurDAO = new JoueurDAO();
		$this->rencontreDAO = new RencontreDAO();
	}

	public function insert(Participant $participant): ?Participant
	{
		$requete = "INSERT INTO participant (idJoueur, idRencontre, typeDeParticipation, poste, evaluation) 
					VALUES (:idJoueur, :idRencontre, :typeDeParticipation, :poste, :evaluation)";

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':idJoueur', $participant->getJoueur()->getId());
		$statement->bindValue(':idRencontre', $participant->getRencontre()->getId());
		$statement->bindValue(':typeDeParticipation', $participant->getTypeDeParticipation()->value);
		$statement->bindValue(':poste', $participant->getPoste()->value);
		$statement->bindValue(':evaluation', $participant->getEvaluation());

		if ($statement->execute()) {
			$participant->setId($this->pdo->lastInsertId());
			return $participant;
		}

		return null;
	}

	public function selectById(int $id): ?Participant
	{
		$requete = "SELECT * FROM participant WHERE id = :id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':id', $id);
		$statement->execute();

		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			$participant = new Participant(
				$row['id'],
				null,
				null,
				TypeDeParticipation::from($row['typeDeParticipation']),
				Poste::from($row['poste']),
				$row['evaluation'] !== null ? (int) $row['evaluation'] : null
			);

			$joueur = $this->joueurDAO->selectById($row['idJoueur']);
			$rencontre = $this->rencontreDAO->selectById($row['idRencontre']);
			$participant->setJoueur($joueur);
			$participant->setRencontre($rencontre);

			return $participant;
		}

		return null;
	}

	public function selectAllByIdRencontre(int $idRencontre): array
	{
		$requete = "SELECT * FROM participant WHERE idRencontre = :idRencontre";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':idRencontre', $idRencontre);
		$statement->execute();

		$participants = [];
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$participant = new Participant(
				$row['id'],
				null,
				null,
				TypeDeParticipation::from($row['typeDeParticipation']),
				Poste::from($row['poste']),
				$row['evaluation'] !== null ? (int) $row['evaluation'] : null
			);

			$joueur = $this->joueurDAO->selectById($row['idJoueur']);
			$rencontre = $this->rencontreDAO->selectById($row['idRencontre']);
			$participant->setJoueur($joueur);
			$participant->setRencontre($rencontre);

			$participants[] = $participant;
		}

		return $participants;
	}

	public function delete(int $id): bool
	{
		$requete = "DELETE FROM participant WHERE id = :id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':id', $id);
		$statement->execute();

		return $statement->rowCount() > 0;
	}

	public function deleteAllByIdRencontre(int $idRencontre): bool
	{
		$requete = "DELETE FROM participant WHERE idRencontre = :idRencontre";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':idRencontre', $idRencontre);
		return $statement->execute();
	}

	public function updateEvaluation(int $idParticipant, ?int $evaluation): bool
	{
		$requete = "UPDATE participant SET evaluation = :evaluation WHERE id = :id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':evaluation', $evaluation);
		$statement->bindValue(':id', $idParticipant);
		return $statement->execute();
	}

	public function getMoyenneEvaluationByIdJoueur(int $idJoueur): float
	{
		$requete = "SELECT AVG(evaluation) as moyenne FROM participant WHERE idJoueur = :idJoueur AND evaluation > 0";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':idJoueur', $idJoueur);
		$statement->execute();
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return $row['moyenne'] ? (float)$row['moyenne'] : 0.0;
	}
}
