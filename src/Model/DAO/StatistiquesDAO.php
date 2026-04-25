<?php

class StatistiquesDAO
{
	private PDO $pdo;

	public function __construct()
	{
		$this->pdo = MySQLDataSource::getInstance()->getConnection();
	}

	public function getJoueursStats(): array
	{
		// Récupère les statistiques agrégées pour chaque joueur
		$requete = "SELECT 
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

		$statement = $this->pdo->prepare($requete);
		$statement->execute();
		$joueursStats = $statement->fetchAll(PDO::FETCH_ASSOC);

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
		$requeteRencontres = 'SELECT rencontre_id FROM rencontre WHERE date_heure <= NOW() OR resultat IS NOT NULL ORDER BY date_heure DESC';
		$statementRencontres = $this->pdo->prepare($requeteRencontres);
		$statementRencontres->execute();
		$rencontres = $statementRencontres->fetchAll(PDO::FETCH_ASSOC);

		$consecutives = 0;
		foreach ($rencontres as $rencontre) {
			// On vérifie si le joueur était présent à ce match précis
			$requeteParticipation = 'SELECT COUNT(*) FROM participant WHERE joueur_id = :joueur_id AND rencontre_id = :rencontre_id';
			$statementParticipation = $this->pdo->prepare($requeteParticipation);
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
