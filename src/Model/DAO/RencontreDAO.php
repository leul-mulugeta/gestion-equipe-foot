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
		$requete = 'INSERT INTO rencontre (date_heure, lieu, adresse, nom_equipe_adverse, resultat, score_equipe_locale, score_equipe_adverse) 
					VALUES (:date_heure, :lieu, :adresse, :nom_equipe_adverse, :resultat, :score_equipe_locale, :score_equipe_adverse)';

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':date_heure', $rencontre->getDateEtHeure()->format('Y-m-d H:i:s'));
		$statement->bindValue(':lieu', $rencontre->getLieu()->value);
		$statement->bindValue(':adresse', $rencontre->getAdresse());
		$statement->bindValue(':nom_equipe_adverse', $rencontre->getNomEquipeAdverse());
		$statement->bindValue(':resultat', $rencontre->getResultat() ? $rencontre->getResultat()->value : null);
		$statement->bindValue(':score_equipe_locale', $rencontre->getScoreEquipeLocale());
		$statement->bindValue(':score_equipe_adverse', $rencontre->getScoreEquipeAdverse());

		if ($statement->execute()) {
			$rencontre->setId($this->pdo->lastInsertId());
			return $rencontre;
		}

		return null;
	}

	public function update(Rencontre $rencontre): ?Rencontre
	{
		$requete = 'UPDATE rencontre SET 
					date_heure = :date_heure, 
					lieu = :lieu, 
					adresse = :adresse, 
					nom_equipe_adverse = :nom_equipe_adverse, 
					resultat = :resultat, 
					score_equipe_locale = :score_equipe_locale, 
					score_equipe_adverse = :score_equipe_adverse 
					WHERE rencontre_id = :rencontre_id';

		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':date_heure', $rencontre->getDateEtHeure()->format('Y-m-d H:i:s'));
		$statement->bindValue(':lieu', $rencontre->getLieu()->value);
		$statement->bindValue(':adresse', $rencontre->getAdresse());
		$statement->bindValue(':nom_equipe_adverse', $rencontre->getNomEquipeAdverse());
		$statement->bindValue(':resultat', $rencontre->getResultat() ? $rencontre->getResultat()->value : null);
		$statement->bindValue(':score_equipe_locale', $rencontre->getScoreEquipeLocale());
		$statement->bindValue(':score_equipe_adverse', $rencontre->getScoreEquipeAdverse());
		$statement->bindValue(':rencontre_id', $rencontre->getId());

		if ($statement->execute()) {
			return $rencontre;
		}

		return null;
	}

	public function selectById(int $id): ?Rencontre
	{
		$requete = 'SELECT * FROM rencontre WHERE rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':rencontre_id', $id);
		$statement->execute();

		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			$rencontre = new Rencontre(
				(int) $row['rencontre_id'],
				new DateTime($row['date_heure']),
				Lieu::from($row['lieu']),
				$row['adresse'],
				$row['nom_equipe_adverse'],
				$row['resultat'] ? Resultat::tryFrom($row['resultat']) : null,
				$row['score_equipe_locale'] !== null ? (int) $row['score_equipe_locale'] : null,
				$row['score_equipe_adverse'] !== null ? (int) $row['score_equipe_adverse'] : null
			);

			return $rencontre;
		}

		return null;
	}

	public function selectAll(): array
	{
		$requete = 'SELECT * FROM rencontre ORDER BY date_heure DESC';
		$statement = $this->pdo->prepare($requete);
		$statement->execute();

		$rencontres = [];
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$rencontre = new Rencontre(
				(int) $row['rencontre_id'],
				new DateTime($row['date_heure']),
				Lieu::from($row['lieu']),
				$row['adresse'],
				$row['nom_equipe_adverse'],
				$row['resultat'] ? Resultat::tryFrom($row['resultat']) : null,
				$row['score_equipe_locale'] !== null ? (int) $row['score_equipe_locale'] : null,
				$row['score_equipe_adverse'] !== null ? (int) $row['score_equipe_adverse'] : null
			);

			$rencontres[] = $rencontre;
		}

		return $rencontres;
	}

	public function delete(int $id): bool
	{
		$requete = 'DELETE FROM rencontre WHERE rencontre_id = :rencontre_id';
		$statement = $this->pdo->prepare($requete);
		$statement->bindValue(':rencontre_id', $id);
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
