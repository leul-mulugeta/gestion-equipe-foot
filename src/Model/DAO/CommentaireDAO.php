<?php

class CommentaireDAO
{
	private PDO $pdo;

	public function __construct()
	{
		$this->pdo = MySQLDataSource::getInstance()->getConnection();
	}

	public function insert(Commentaire $commentaire, int $joueurId): ?Commentaire
	{
		$requete = "INSERT INTO commentaire (joueur_id, contenu) VALUES (:joueur_id, :contenu)";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':joueur_id', $joueurId);
		$statement->bindValue(':contenu', $commentaire->getContenu());

		if ($statement->execute()) {
			$commentaire->setCommentaireId($this->pdo->lastInsertId());
			return $commentaire;
		}

		return null;
	}

	public function selectByIdJoueur(int $idJoueur): array
	{
		$requete = "SELECT * FROM commentaire WHERE joueur_id = :joueur_id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':joueur_id', $idJoueur);
		$statement->execute();

		$commentaires = [];
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$commentaire = new Commentaire(
				$row['commentaire_id'],
				$row['contenu']
			);

			$commentaires[] = $commentaire;
		}

		return $commentaires;
	}

	public function delete(int $id): bool
	{
		$requete = "DELETE FROM commentaire WHERE commentaire_id = :commentaire_id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':commentaire_id', $id);
		$statement->execute();

		return $statement->rowCount() > 0;
	}
}
