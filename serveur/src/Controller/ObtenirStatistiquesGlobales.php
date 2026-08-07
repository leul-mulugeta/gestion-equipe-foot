<?php

class ObtenirStatistiquesGlobales
{
	private readonly RencontreDAO $rencontreDAO;

	public function __construct()
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
	}

	public function executer(): array
	{
		$victoires = 0;
		$defaites = 0;
		$nuls = 0;

		foreach ($this->rencontreDAO->selectAllRencontres() as $rencontre) {
			$resultat = $rencontre->getResultat();
			if ($resultat === Resultat::VICTOIRE) {
				$victoires++;
			} elseif ($resultat === Resultat::DEFAITE) {
				$defaites++;
			} elseif ($resultat === Resultat::NUL) {
				$nuls++;
			}
		}

		$total = $victoires + $defaites + $nuls;
		if ($total === 0) {
			return [
				'total' => 0,
				'victoires' => 0,
				'defaites' => 0,
				'nuls' => 0,
				'pourcentageVictoires' => 0,
				'pourcentageDefaites' => 0,
				'pourcentageNuls' => 0
			];
		}

		return [
			'total' => $total,
			'victoires' => $victoires,
			'defaites' => $defaites,
			'nuls' => $nuls,
			'pourcentageVictoires' => round(($victoires / $total) * 100, 2),
			'pourcentageDefaites' => round(($defaites / $total) * 100, 2),
			'pourcentageNuls' => round(($nuls / $total) * 100, 2)
		];
	}
}
