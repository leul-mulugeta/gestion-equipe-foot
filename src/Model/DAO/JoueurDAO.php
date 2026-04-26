<?php

class JoueurDAO
{
	private static ?JoueurDAO $instance = null;
	private readonly PDO $pdo;

	private function __construct()
	{
		$this->pdo = DBConnection::getInstance()->getConnection();
	}

	public static function getInstance(): JoueurDAO
	{
		if (self::$instance === null) {
			self::$instance = new JoueurDAO();
		}
		return self::$instance;
	}

	public function insertJoueur(Joueur $joueur): void
	{
		$query = 'INSERT INTO joueur (numero_licence, nom, prenom, date_naissance, taille, poids, statut, poste) 
					VALUES (:numero_licence, :nom, :prenom, :date_naissance, :taille, :poids, :statut, :poste)';

		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':numero_licence', $joueur->getNumeroDeLicence());
		$statement->bindValue(':nom', $joueur->getNom());
		$statement->bindValue(':prenom', $joueur->getPrenom());
		$statement->bindValue(':date_naissance', $joueur->getDateDeNaissance()->format('Y-m-d'));
		$statement->bindValue(':taille', $joueur->getTaille());
		$statement->bindValue(':poids', $joueur->getPoids());
		$statement->bindValue(':statut', $joueur->getStatut()->value);
		$statement->bindValue(':poste', $joueur->getPoste()->value);
		$statement->execute();
	}

	public function updateJoueur(Joueur $joueur): void
	{
		$query = 'UPDATE joueur SET
					numero_licence = :numero_licence,
					nom = :nom,
					prenom = :prenom,
					date_naissance = :date_naissance,
					taille = :taille,
					poids = :poids,
					statut = :statut,
					poste = :poste
					WHERE joueur_id = :joueur_id';

		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':numero_licence', $joueur->getNumeroDeLicence());
		$statement->bindValue(':nom', $joueur->getNom());
		$statement->bindValue(':prenom', $joueur->getPrenom());
		$statement->bindValue(':date_naissance', $joueur->getDateDeNaissance()->format('Y-m-d'));
		$statement->bindValue(':taille', $joueur->getTaille());
		$statement->bindValue(':poids', $joueur->getPoids());
		$statement->bindValue(':statut', $joueur->getStatut()->value);
		$statement->bindValue(':poste', $joueur->getPoste()->value);
		$statement->bindValue(':joueur_id', $joueur->getJoueurId());
		$statement->execute();
	}

	public function selectJoueurById(int $joueurId): ?Joueur
	{
		$query = 'SELECT * FROM joueur WHERE joueur_id = :joueur_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':joueur_id', $joueurId);
		$statement->execute();

		$dbLine = $statement->fetch();
		if (!$dbLine) {
			return null;
		}

		return $this->arrayToJoueur($dbLine);
	}

	public function selectAllJoueurs(): array
	{
		$query = 'SELECT * FROM joueur';
		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return array_map(fn($dbLine) => $this->arrayToJoueur($dbLine), $statement->fetchAll());
	}

	public function deleteJoueur(int $joueurId): bool
	{
		$query = 'DELETE FROM joueur WHERE joueur_id = :joueur_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':joueur_id', $joueurId);
		$statement->execute();

		return $statement->rowCount() > 0;
	}

	private function arrayToJoueur(array $dbLine): Joueur
	{
		return new Joueur(
			$dbLine['joueur_id'],
			(int) $dbLine['numero_licence'],
			$dbLine['nom'],
			$dbLine['prenom'],
			new DateTime($dbLine['date_naissance']),
			(int) $dbLine['taille'],
			(float) $dbLine['poids'],
			Statut::from($dbLine['statut']),
			Poste::from($dbLine['poste'])
		);
	}
}
