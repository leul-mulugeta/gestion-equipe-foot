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

	public function selectAgregatsParticipationParJoueur(): array
	{
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
						COUNT(p.participant_id) as totalParticipations
					FROM joueur j
					LEFT JOIN participant p ON j.joueur_id = p.joueur_id
					GROUP BY j.joueur_id";

		$statement = $this->pdo->prepare($query);
		$statement->execute();

		return $statement->fetchAll();
	}

	// Toutes les paires joueur/rencontre en une seule requête, indexées par joueur puis par
	// rencontre, pour éviter une interrogation par joueur et par rencontre
	public function selectParticipationsParJoueur(): array
	{
		$query = 'SELECT joueur_id, rencontre_id FROM participant';
		$statement = $this->pdo->prepare($query);
		$statement->execute();

		$participations = [];
		foreach ($statement->fetchAll() as $dbLine) {
			$participations[(int) $dbLine['joueur_id']][(int) $dbLine['rencontre_id']] = true;
		}

		return $participations;
	}
}
