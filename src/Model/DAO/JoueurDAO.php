<?php

class JoueurDAO
{
	private PDO $pdo;
	private CommentaireDAO $commentaireDAO;

	public function __construct()
	{
		$this->pdo = MySQLDataSource::getInstance()->getConnection();
		$this->commentaireDAO = new CommentaireDAO();
	}

	public function insert(Joueur $joueur): ?Joueur
	{
		$requete = "INSERT INTO joueur (numeroDeLicence, nom, prenom, dateDeNaissance, taille, poids, statut, poste) 
					VALUES (:numeroDeLicence, :nom, :prenom, :dateDeNaissance, :taille, :poids, :statut, :poste)";

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':numeroDeLicence', $joueur->getNumeroDeLicence());
		$statement->bindValue(':nom', $joueur->getNom());
		$statement->bindValue(':prenom', $joueur->getPrenom());
		$statement->bindValue(':dateDeNaissance', $joueur->getDateDeNaissance()->format('Y-m-d'));
		$statement->bindValue(':taille', $joueur->getTaille());
		$statement->bindValue(':poids', $joueur->getPoids());
		$statement->bindValue(':statut', $joueur->getStatut()->value);
		$statement->bindValue(':poste', $joueur->getPoste()->value);

		if ($statement->execute()) {
			$joueur->setId($this->pdo->lastInsertId());
			return $joueur;
		}

		return null;
	}

	public function update(Joueur $joueur): ?Joueur
	{
		$requete = "UPDATE joueur SET
					numeroDeLicence = :numeroDeLicence,
					nom = :nom,
					prenom = :prenom,
					dateDeNaissance = :dateDeNaissance,
					taille = :taille,
					poids = :poids,
					statut = :statut,
					poste = :poste
					WHERE id = :id";

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':numeroDeLicence', $joueur->getNumeroDeLicence());
		$statement->bindValue(':nom', $joueur->getNom());
		$statement->bindValue(':prenom', $joueur->getPrenom());
		$statement->bindValue(':dateDeNaissance', $joueur->getDateDeNaissance()->format('Y-m-d'));
		$statement->bindValue(':taille', $joueur->getTaille());
		$statement->bindValue(':poids', $joueur->getPoids());
		$statement->bindValue(':statut', $joueur->getStatut()->value);
		$statement->bindValue(':poste', $joueur->getPoste()->value);
		$statement->bindValue(':id', $joueur->getId());

		if ($statement->execute()) {
			return $joueur;
		}

		return null;
	}

	public function selectById(int $id): ?Joueur
	{
		$requete = "SELECT * FROM joueur WHERE id = :id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':id', $id);
		$statement->execute();

		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			$joueur = new Joueur(
				$row['id'],
				(int) $row['numeroDeLicence'],
				$row['nom'],
				$row['prenom'],
				new DateTime($row['dateDeNaissance']),
				(int) $row['taille'],
				(float) $row['poids'],
				Statut::from($row['statut']),
				Poste::from($row['poste'])
			);

			$commentaires = $this->commentaireDAO->selectByIdJoueur($joueur->getId());

			foreach ($commentaires as $commentaire) {
				$commentaire->setJoueur($joueur);
				$joueur->addCommentaire($commentaire);
			}

			return $joueur;
		}

		return null;
	}

	public function selectAll(): array
	{
		$requete = "SELECT * FROM joueur";
		$statement = $this->pdo->prepare($requete);
		$statement->execute();

		$joueurs = [];
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$joueur = new Joueur(
				$row['id'],
				(int) $row['numeroDeLicence'],
				$row['nom'],
				$row['prenom'],
				new DateTime($row['dateDeNaissance']),
				(int) $row['taille'],
				(float) $row['poids'],
				Statut::from($row['statut']),
				Poste::from($row['poste'])
			);

			$commentaires = $this->commentaireDAO->selectByIdJoueur($joueur->getId());

			foreach ($commentaires as $commentaire) {
				$commentaire->setJoueur($joueur);
				$joueur->addCommentaire($commentaire);
			}

			$joueurs[] = $joueur;
		}

		return $joueurs;
	}

	public function delete(int $id): bool
	{
		$requete = "DELETE FROM joueur WHERE id = :id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':id', $id);
		$statement->execute();

		return $statement->rowCount() > 0;
	}
}
