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
						j.id, 
						j.nom, 
						j.prenom, 
						j.statut,
						/* Sous-requête pour trouver le poste où le joueur a la meilleure moyenne d'évaluation */
						(SELECT p1.poste FROM participant p1 WHERE p1.idJoueur = j.id GROUP BY p1.poste ORDER BY AVG(p1.evaluation) DESC LIMIT 1) as postePrefere,
						SUM(CASE WHEN p.typeDeParticipation = 'TITULAIRE' THEN 1 ELSE 0 END) as titularisations,
						SUM(CASE WHEN p.typeDeParticipation = 'REMPLACANT' THEN 1 ELSE 0 END) as remplacements,
						AVG(p.evaluation) as moyenneEvaluations,
						COUNT(p.id) as totalParticipations,
						SUM(CASE WHEN r.resultat = 'VICTOIRE' THEN 1 ELSE 0 END) as matchsGagnes
					FROM joueur j
					LEFT JOIN participant p ON j.id = p.idJoueur
					LEFT JOIN rencontre r ON p.idRencontre = r.id AND r.resultat IS NOT NULL
					GROUP BY j.id";

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
			$stats['selectionsConsecutives'] = $this->getSelectionsConsecutives($stats['id']);
		}

		return $joueursStats;
	}

	private function getSelectionsConsecutives(int $idJoueur): int
	{
		// On récupère toutes les rencontres passées (qui ont un résultat ou dont la date est passée)
		// Ordonnées par date décroissante pour vérifier la série actuelle
		$requeteRencontres = "SELECT id FROM rencontre WHERE dateEtHeure <= NOW() OR resultat IS NOT NULL ORDER BY dateEtHeure DESC";
		$statementRencontres = $this->pdo->prepare($requeteRencontres);
		$statementRencontres->execute();
		$rencontres = $statementRencontres->fetchAll(PDO::FETCH_ASSOC);

		$consecutives = 0;
		foreach ($rencontres as $rencontre) {
			// On vérifie si le joueur était présent à ce match précis
			$requeteParticipation = "SELECT COUNT(*) FROM participant WHERE idJoueur = :idJoueur AND idRencontre = :idRencontre";
			$statementParticipation = $this->pdo->prepare($requeteParticipation);
			$statementParticipation->bindValue(':idJoueur', $idJoueur);
			$statementParticipation->bindValue(':idRencontre', $rencontre['id']);
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
