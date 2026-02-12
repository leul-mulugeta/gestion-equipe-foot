<?php

class RencontreDAO
{
	private PDO $pdo;

	public function __construct()
	{
		$this->pdo = MySQLDataSource::getInstance()->getConnection();
	}

	public function insert(Rencontre $rencontre): ?Rencontre
	{
		$requete = "INSERT INTO rencontre (dateEtHeure, lieu, adresse, nomEquipeAdverse, resultat, scoreEquipeLocale, scoreEquipeAdverse) 
					VALUES (:dateEtHeure, :lieu, :adresse, :nomEquipeAdverse, :resultat, :scoreEquipeLocale, :scoreEquipeAdverse)";

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':dateEtHeure', $rencontre->getDateEtHeure()->format('Y-m-d H:i:s'));
		$statement->bindValue(':lieu', $rencontre->getLieu()->value);
		$statement->bindValue(':adresse', $rencontre->getAdresse());
		$statement->bindValue(':nomEquipeAdverse', $rencontre->getNomEquipeAdverse());
		$statement->bindValue(':resultat', $rencontre->getResultat() ? $rencontre->getResultat()->value : null);
		$statement->bindValue(':scoreEquipeLocale', $rencontre->getScoreEquipeLocale());
		$statement->bindValue(':scoreEquipeAdverse', $rencontre->getScoreEquipeAdverse());

		if ($statement->execute()) {
			$rencontre->setId($this->pdo->lastInsertId());
			return $rencontre;
		}

		return null;
	}

	public function update(Rencontre $rencontre): ?Rencontre
	{
		$requete = "UPDATE rencontre SET 
					dateEtHeure = :dateEtHeure, 
					lieu = :lieu, 
					adresse = :adresse, 
					nomEquipeAdverse = :nomEquipeAdverse, 
					resultat = :resultat, 
					scoreEquipeLocale = :scoreEquipeLocale, 
					scoreEquipeAdverse = :scoreEquipeAdverse 
					WHERE id = :id";

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':dateEtHeure', $rencontre->getDateEtHeure()->format('Y-m-d H:i:s'));
		$statement->bindValue(':lieu', $rencontre->getLieu()->value);
		$statement->bindValue(':adresse', $rencontre->getAdresse());
		$statement->bindValue(':nomEquipeAdverse', $rencontre->getNomEquipeAdverse());
		$statement->bindValue(':resultat', $rencontre->getResultat() ? $rencontre->getResultat()->value : null);
		$statement->bindValue(':scoreEquipeLocale', $rencontre->getScoreEquipeLocale());
		$statement->bindValue(':scoreEquipeAdverse', $rencontre->getScoreEquipeAdverse());
		$statement->bindValue(':id', $rencontre->getId());

		if ($statement->execute()) {
			return $rencontre;
		}

		return null;
	}

	public function selectById(int $id): ?Rencontre
	{
		$requete = "SELECT * FROM rencontre WHERE id = :id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':id', $id);
		$statement->execute();

		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			$rencontre = new Rencontre(
				(int) $row['id'],
				new DateTime($row['dateEtHeure']),
				Lieu::from($row['lieu']),
				$row['adresse'],
				$row['nomEquipeAdverse'],
				$row['resultat'] ? Resultat::tryFrom($row['resultat']) : null,
				$row['scoreEquipeLocale'] !== null ? (int) $row['scoreEquipeLocale'] : null,
				$row['scoreEquipeAdverse'] !== null ? (int) $row['scoreEquipeAdverse'] : null
			);

			return $rencontre;
		}

		return null;
	}

	public function selectAll(): array
	{
		$requete = "SELECT * FROM rencontre ORDER BY dateEtHeure DESC";
		$statement = $this->pdo->prepare($requete);
		$statement->execute();

		$rencontres = [];
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$rencontre = new Rencontre(
				(int) $row['id'],
				new DateTime($row['dateEtHeure']),
				Lieu::from($row['lieu']),
				$row['adresse'],
				$row['nomEquipeAdverse'],
				$row['resultat'] ? Resultat::tryFrom($row['resultat']) : null,
				$row['scoreEquipeLocale'] !== null ? (int) $row['scoreEquipeLocale'] : null,
				$row['scoreEquipeAdverse'] !== null ? (int) $row['scoreEquipeAdverse'] : null
			);

			$rencontres[] = $rencontre;
		}

		return $rencontres;
	}

	public function delete(int $id): bool
	{
		$requete = "DELETE FROM rencontre WHERE id = :id";
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':id', $id);
		$statement->execute();

		return $statement->rowCount() > 0;
	}

	public function getGlobalStats(): array
	{
		$requete = "SELECT 
						COUNT(*) as total,
						SUM(CASE WHEN resultat = 'VICTOIRE' THEN 1 ELSE 0 END) as victoires,
						SUM(CASE WHEN resultat = 'DEFAITE' THEN 1 ELSE 0 END) as defaites,
						SUM(CASE WHEN resultat = 'NUL' THEN 1 ELSE 0 END) as nuls
					FROM rencontre 
					WHERE resultat IS NOT NULL";

		$statement = $this->pdo->prepare($requete);
		$statement->execute();
		$row = $statement->fetch(PDO::FETCH_ASSOC);

		if ($row['total'] > 0) {
			$stats = [
				'total' => (int)$row['total'],
				'victoires' => (int)$row['victoires'],
				'defaites' => (int)$row['defaites'],
				'nuls' => (int)$row['nuls'],
				'pourcentageVictoires' => round(($row['victoires'] / $row['total']) * 100, 2),
				'pourcentageDefaites' => round(($row['defaites'] / $row['total']) * 100, 2),
				'pourcentageNuls' => round(($row['nuls'] / $row['total']) * 100, 2),
			];
		} else {
			$stats = [
				'total' => 0,
				'victoires' => 0,
				'defaites' => 0,
				'nuls' => 0,
				'pourcentageVictoires' => 0,
				'pourcentageDefaites' => 0,
				'pourcentageNuls' => 0,
			];
		}

		return $stats;
	}
}
