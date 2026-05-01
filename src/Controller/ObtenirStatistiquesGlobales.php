<?php

class ObtenirStatistiquesGlobales
{
	private readonly StatistiquesDAO $statistiquesDAO;

	public function __construct()
	{
		$this->statistiquesDAO = StatistiquesDAO::getInstance();
	}

	public function executer(): array
	{
		return $this->statistiquesDAO->getGlobalStats();
	}
}
