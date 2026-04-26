<?php

class JoueurDAO
{
	private PDO $pdo;

	public function __construct()
	{
		$this->pdo = MySQLDataSource::getInstance()->getConnection();
	}

	public function insert(Joueur $joueur): ?Joueur
	{
		$requete = 'INSERT INTO joueur (numero_licence, nom, prenom, date_naissance, taille, poids, statut, poste) 
					VALUES (:numero_licence, :nom, :prenom, :date_naissance, :taille, :poids, :statut, :poste)';

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':numero_licence', $joueur->getNumeroDeLicence());
		$statement->bindValue(':nom', $joueur->getNom());
		$statement->bindValue(':prenom', $joueur->getPrenom());
		$statement->bindValue(':date_naissance', $joueur->getDateDeNaissance()->format('Y-m-d'));
		$statement->bindValue(':taille', $joueur->getTaille());
		$statement->bindValue(':poids', $joueur->getPoids());
		$statement->bindValue(':statut', $joueur->getStatut()->value);
		$statement->bindValue(':poste', $joueur->getPoste()->value);

		if ($statement->execute()) {
			$joueur->setJoueurId($this->pdo->lastInsertId());
			return $joueur;
		}

		return null;
	}

	public function update(Joueur $joueur): ?Joueur
	{
		$requete = 'UPDATE joueur SET
					numero_licence = :numero_licence,
					nom = :nom,
					prenom = :prenom,
					date_naissance = :date_naissance,
					taille = :taille,
					poids = :poids,
					statut = :statut,
					poste = :poste
					WHERE joueur_id = :joueur_id';

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':numero_licence', $joueur->getNumeroDeLicence());
		$statement->bindValue(':nom', $joueur->getNom());
		$statement->bindValue(':prenom', $joueur->getPrenom());
		$statement->bindValue(':date_naissance', $joueur->getDateDeNaissance()->format('Y-m-d'));
		$statement->bindValue(':taille', $joueur->getTaille());
		$statement->bindValue(':poids', $joueur->getPoids());
		$statement->bindValue(':statut', $joueur->getStatut()->value);
		$statement->bindValue(':poste', $joueur->getPoste()->value);
		$statement->bindValue(':joueur_id', $joueur->getJoueurId());

		if ($statement->execute()) {
			return $joueur;
		}

		return null;
	}

	public function selectById(int $id): ?Joueur
	{
		$requete = 'SELECT * FROM joueur WHERE joueur_id = :joueur_id';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':joueur_id', $id);
		$statement->execute();

		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			$joueur = new Joueur(
				$row['joueur_id'],
				(int) $row['numero_licence'],
				$row['nom'],
				$row['prenom'],
				new DateTime($row['date_naissance']),
				(int) $row['taille'],
				(float) $row['poids'],
				Statut::from($row['statut']),
				Poste::from($row['poste'])
			);

			return $joueur;
		}

		return null;
	}

	public function selectAll(): array
	{
		$requete = 'SELECT * FROM joueur';
		$statement = $this->pdo->prepare($requete);
		$statement->execute();

		$joueurs = [];
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$joueur = new Joueur(
				$row['joueur_id'],
				(int) $row['numero_licence'],
				$row['nom'],
				$row['prenom'],
				new DateTime($row['date_naissance']),
				(int) $row['taille'],
				(float) $row['poids'],
				Statut::from($row['statut']),
				Poste::from($row['poste'])
			);

			$joueurs[] = $joueur;
		}

		return $joueurs;
	}

	public function delete(int $id): bool
	{
		$requete = 'DELETE FROM joueur WHERE joueur_id = :joueur_id';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':joueur_id', $id);
		$statement->execute();

		return $statement->rowCount() > 0;
	}
}
