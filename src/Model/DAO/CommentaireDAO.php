<?php

class CommentaireDAO
{
	private PDO $pdo;

	public function __construct()
	{
		$this->pdo = MySQLDataSource::getInstance()->getConnection();
	}

	public function insert(Commentaire $commentaire): ?Commentaire
	{
		$requete = "INSERT INTO commentaire (idJoueur, note) VALUES (:idJoueur, :note)";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':idJoueur', $commentaire->getJoueur()->getId());
		$statement->bindValue(':note', $commentaire->getNote());

		if ($statement->execute()) {
			$commentaire->setId($this->pdo->lastInsertId());
			return $commentaire;
		}

		return null;
	}

	public function selectByIdJoueur(int $idJoueur): array
	{
		$requete = "SELECT * FROM commentaire WHERE idJoueur = :idJoueur";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':idJoueur', $idJoueur);
		$statement->execute();

		$commentaires = [];
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$commentaire = new Commentaire(
				$row['id'],
				null,
				$row['note']
			);

			$commentaires[] = $commentaire;
		}

		return $commentaires;
	}

	public function delete(int $id): bool
	{
		$requete = "DELETE FROM commentaire WHERE id = :id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':id', $id);
		$statement->execute();

		return $statement->rowCount() > 0;
	}
}
