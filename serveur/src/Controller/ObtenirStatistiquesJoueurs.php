<?php

class ObtenirStatistiquesJoueurs
{
	private readonly StatistiquesDAO $statistiquesDAO;
	private readonly RencontreDAO $rencontreDAO;

	public function __construct()
	{
		$this->statistiquesDAO = StatistiquesDAO::getInstance();
		$this->rencontreDAO = RencontreDAO::getInstance();
	}

	public function executer(): array
	{
		// Structure obtenue, à deux niveaux :
		//   $participations[1][5] === true   <=>   le joueur 1 a participé à la rencontre 5
		$participations = $this->statistiquesDAO->selectParticipationsParJoueur();

		// Rencontres dont la feuille de match a été saisie
		$rencontresAvecFeuille = [];
		foreach ($participations as $rencontresDuJoueur) {
			foreach (array_keys($rencontresDuJoueur) as $rencontreId) {
				$rencontresAvecFeuille[$rencontreId] = true;
			}
		}

		$idsRencontresAvecResultat = [];
		$idsRencontresGagnees = [];
		$idsRencontresJoueesAvecFeuille = [];

		foreach ($this->rencontreDAO->selectAllRencontres() as $rencontre) {
			$rencontreId = $rencontre->getRencontreId();
			$resultat = $rencontre->getResultat();

			if ($resultat !== null) {
				$idsRencontresAvecResultat[$rencontreId] = true;
				if ($resultat === Resultat::VICTOIRE) {
					$idsRencontresGagnees[$rencontreId] = true;
				}
			}

			// Une rencontre n'entre dans les séries que si elle a eu lieu ET qu'on sait qui y a joué (feuille de match existe)
			if ($rencontre->getDateEtHeure() <= new DateTime() && isset($rencontresAvecFeuille[$rencontreId])) {
				$idsRencontresJoueesAvecFeuille[] = $rencontreId;
			}
		}

		$joueursStats = $this->statistiquesDAO->selectAgregatsParticipationParJoueur();

		foreach ($joueursStats as &$stats) {
			$joueurId = (int) $stats['joueur_id'];

			// Les rencontres de CE joueur, sous la forme [rencontreId => true]
			// Un joueur jamais sélectionné est absent de $participations, d'où le ?? []
			$participationsDuJoueur = $participations[$joueurId] ?? [];

			$stats['joueur_id'] = $joueurId;
			$stats['titularisations'] = (int) $stats['titularisations'];
			$stats['remplacements'] = (int) $stats['remplacements'];
			$stats['totalParticipations'] = (int) $stats['totalParticipations'];
			$stats['moyenneEvaluations'] = $stats['moyenneEvaluations'] !== null
				? round((float) $stats['moyenneEvaluations'], 2)
				: 0;

			$nbRencontresGagnees = 0;
			$nbRencontresAvecResultat = 0;
			foreach (array_keys($participationsDuJoueur) as $rencontreId) {
				if (!isset($idsRencontresAvecResultat[$rencontreId])) {
					continue;
				}
				$nbRencontresAvecResultat++;
				if (isset($idsRencontresGagnees[$rencontreId])) {
					$nbRencontresGagnees++;
				}
			}

			$stats['rencontresGagnees'] = $nbRencontresGagnees;
			$stats['pourcentageGagnes'] = $nbRencontresAvecResultat > 0
				? round(($nbRencontresGagnees / $nbRencontresAvecResultat) * 100, 2)
				: 0;

			$stats['meilleureSerieSelections'] = $this->compterMeilleureSerieSelections($idsRencontresJoueesAvecFeuille, $participationsDuJoueur);
		}
		unset($stats);

		return $joueursStats;
	}

	// Plus longue suite de rencontres consécutives auxquelles le joueur a participé
	private function compterMeilleureSerieSelections(array $idsRencontres, array $participationsDuJoueur): int
	{
		$meilleureSerie = 0;
		$serieCourante = 0;

		foreach ($idsRencontres as $rencontreId) {
			// Rencontre manquée : la série en cours est cassée
			// On ne s'arrête pas car une série plus longue peut exister plus loin dans l'historique
			if (!isset($participationsDuJoueur[$rencontreId])) {
				$serieCourante = 0;
				continue;
			}

			$serieCourante++;
			if ($serieCourante > $meilleureSerie) {
				$meilleureSerie = $serieCourante;
			}
		}

		return $meilleureSerie;
	}
}
