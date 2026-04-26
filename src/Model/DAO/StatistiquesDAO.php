<?php

class StatistiquesDAO
{
	private static ?StatistiquesDAO $instance = null;
	private readonly PDO $pdo;

	private function __construct()
	{
		$this->pdo = DBConnection::getInstance()->getConnection();
	}

	public static function getInstance(): StatistiquesDAO
	{
		if (self::$instance === null) {
			self::$instance = new StatistiquesDAO();
		}
		return self::$instance;
	}

	public function getGlobalStats(): array
	{
		$query = "SELECT 
						COUNT(*) as total,
						SUM(CASE WHEN resultat = 'VICTOIRE' THEN 1 ELSE 0 END) as victoires,
						SUM(CASE WHEN resultat = 'DEFAITE' THEN 1 ELSE 0 END) as defaites,
						SUM(CASE WHEN resultat = 'NUL' THEN 1 ELSE 0 END) as nuls
					FROM rencontre 
					WHERE resultat IS NOT NULL";

		$statement = $this->pdo->prepare($query);
		$statement->execute();
		$dbLine = $statement->fetch();

		if ($dbLine['total'] > 0) {
			$stats = [
				'total' => (int)$dbLine['total'],
				'victoires' => (int)$dbLine['victoires'],
				'defaites' => (int)$dbLine['defaites'],
				'nuls' => (int)$dbLine['nuls'],
				'pourcentageVictoires' => round(($dbLine['victoires'] / $dbLine['total']) * 100, 2),
				'pourcentageDefaites' => round(($dbLine['defaites'] / $dbLine['total']) * 100, 2),
				'pourcentageNuls' => round(($dbLine['nuls'] / $dbLine['total']) * 100, 2),
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

	public function getJoueursStats(): array
	{
		// Récupère les statistiques agrégées pour chaque joueur
		$query = "SELECT 
						j.joueur_id, 
						j.nom, 
						j.prenom, 
						j.statut,
						/* Sous-requête pour trouver le poste où le joueur a la meilleure moyenne d'évaluation */
						(SELECT p1.poste FROM participant p1 WHERE p1.joueur_id = j.joueur_id GROUP BY p1.poste ORDER BY AVG(p1.evaluation) DESC LIMIT 1) as postePrefere,
						SUM(CASE WHEN p.type_participation = 'TITULAIRE' THEN 1 ELSE 0 END) as titularisations,
						SUM(CASE WHEN p.type_participation = 'REMPLACANT' THEN 1 ELSE 0 END) as remplacements,
						AVG(p.evaluation) as moyenneEvaluations,
						COUNT(p.participant_id) as totalParticipations,
						SUM(CASE WHEN r.resultat = 'VICTOIRE' THEN 1 ELSE 0 END) as matchsGagnes
					FROM joueur j
					LEFT JOIN participant p ON j.joueur_id = p.joueur_id
					LEFT JOIN rencontre r ON p.rencontre_id = r.rencontre_id AND r.resultat IS NOT NULL
					GROUP BY j.joueur_id";

		$statement = $this->pdo->prepare($query);
		$statement->execute();
		$joueursStats = $statement->fetchAll();

		foreach ($joueursStats as &$stats) {
			// Calcul des pourcentages et formatage des moyennes
			$stats['pourcentageGagnes'] = $stats['totalParticipations'] > 0
				? round(($stats['matchsGagnes'] / $stats['totalParticipations']) * 100, 2)
				: 0;
			$stats['moyenneEvaluations'] = $stats['moyenneEvaluations'] !== null
				? round((float)$stats['moyenneEvaluations'], 2)
				: 0;

			// Calcul de la série de participations consécutives
			$stats['selectionsConsecutives'] = $this->getSelectionsConsecutives($stats['joueur_id']);
		}

		return $joueursStats;
	}

	private function getSelectionsConsecutives(int $idJoueur): int
	{
		// On récupère toutes les rencontres passées (qui ont un résultat ou dont la date est passée)
		// Ordonnées par date décroissante pour vérifier la série actuelle
		$queryRencontres = 'SELECT rencontre_id FROM rencontre WHERE date_heure <= NOW() OR resultat IS NOT NULL ORDER BY date_heure DESC';
		$statementRencontres = $this->pdo->prepare($queryRencontres);
		$statementRencontres->execute();
		$rencontres = $statementRencontres->fetchAll();

		$consecutives = 0;
		foreach ($rencontres as $rencontre) {
			// On vérifie si le joueur était présent à ce match précis
			$queryParticipation = 'SELECT COUNT(*) FROM participant WHERE joueur_id = :joueur_id AND rencontre_id = :rencontre_id';
			$statementParticipation = $this->pdo->prepare($queryParticipation);
			$statementParticipation->bindValue(':joueur_id', $idJoueur);
			$statementParticipation->bindValue(':rencontre_id', $rencontre['rencontre_id']);
			$statementParticipation->execute();

			if ($statementParticipation->fetchColumn() > 0) {
				$consecutives++;
			} else {
				// La série s'arrête dès qu'un match est manqué
				break;
			}
		}

		return $consecutives;
	}
}
