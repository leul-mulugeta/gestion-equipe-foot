<?php

class RencontreDAO
{
	private static ?RencontreDAO $instance = null;
	private readonly PDO $pdo;

	private function __construct()
	{
		$this->pdo = DBConnection::getInstance()->getConnection();
	}

	public static function getInstance(): RencontreDAO
	{
		if (self::$instance === null) {
			self::$instance = new RencontreDAO();
		}
		return self::$instance;
	}

	public function insertRencontre(Rencontre $rencontre): void
	{
		$query = 'INSERT INTO rencontre (date_heure, lieu, adresse, nom_equipe_adverse)
					VALUES (:date_heure, :lieu, :adresse, :nom_equipe_adverse)';

		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':date_heure', $rencontre->getDateEtHeure()->format('Y-m-d H:i:s'));
		$statement->bindValue(':lieu', $rencontre->getLieu()->value);
		$statement->bindValue(':adresse', $rencontre->getAdresse());
		$statement->bindValue(':nom_equipe_adverse', $rencontre->getNomEquipeAdverse());
		$statement->execute();
	}

	public function updatePlanification(Rencontre $rencontre): void
	{
		$query = 'UPDATE rencontre SET
					date_heure = :date_heure,
					lieu = :lieu,
					adresse = :adresse,
					nom_equipe_adverse = :nom_equipe_adverse
					WHERE rencontre_id = :rencontre_id';

		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':date_heure', $rencontre->getDateEtHeure()->format('Y-m-d H:i:s'));
		$statement->bindValue(':lieu', $rencontre->getLieu()->value);
		$statement->bindValue(':adresse', $rencontre->getAdresse());
		$statement->bindValue(':nom_equipe_adverse', $rencontre->getNomEquipeAdverse());
		$statement->bindValue(':rencontre_id', $rencontre->getRencontreId());
		$statement->execute();
	}

	public function updateScores(int $rencontreId, int $scoreEquipeLocale, int $scoreEquipeAdverse): void
	{
		$query = 'UPDATE rencontre SET
					score_equipe_locale = :score_equipe_locale,
					score_equipe_adverse = :score_equipe_adverse
					WHERE rencontre_id = :rencontre_id';

		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':score_equipe_locale', $scoreEquipeLocale);
		$statement->bindValue(':score_equipe_adverse', $scoreEquipeAdverse);
		$statement->bindValue(':rencontre_id', $rencontreId);
		$statement->execute();
	}

	public function selectRencontreById(int $rencontreId): Rencontre
	{
		$query = 'SELECT * FROM rencontre WHERE rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':rencontre_id', $rencontreId);
		$statement->execute();

		$dbLine = $statement->fetch();
		if (!$dbLine) {
			throw new NonTrouveException("Cette rencontre n'existe pas.");
		}

		return $this->arrayToRencontre($dbLine);
	}

	public function selectAllRencontres(): array
	{
		$query = 'SELECT * FROM rencontre ORDER BY date_heure DESC';
		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return array_map(fn($dbLine) => $this->arrayToRencontre($dbLine), $statement->fetchAll());
	}

	public function deleteRencontre(int $rencontreId): void
	{
		$query = 'DELETE FROM rencontre WHERE rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':rencontre_id', $rencontreId);
		$statement->execute();
	}

	private function arrayToRencontre(array $dbLine): Rencontre
	{
		return new Rencontre(
			(int) $dbLine['rencontre_id'],
			new DateTime($dbLine['date_heure']),
			Lieu::from($dbLine['lieu']),
			$dbLine['adresse'],
			$dbLine['nom_equipe_adverse'],
			$dbLine['score_equipe_locale'] !== null ? (int) $dbLine['score_equipe_locale'] : null,
			$dbLine['score_equipe_adverse'] !== null ? (int) $dbLine['score_equipe_adverse'] : null
		);
	}
}
