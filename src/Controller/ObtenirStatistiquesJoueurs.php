<?php

class ObtenirStatistiquesJoueurs
{
	private StatistiquesDAO $statistiquesDAO;

	public function __construct()
	{
		$this->statistiquesDAO = new StatistiquesDAO();
	}

	public function executer(): array
	{
		return $this->statistiquesDAO->getJoueursStats();
	}
}
