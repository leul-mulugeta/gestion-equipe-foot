<?php

class ObtenirStatistiquesGlobales
{
	private RencontreDAO $rencontreDAO;

	public function __construct()
	{
		$this->rencontreDAO = new RencontreDAO();
	}

	public function executer(): array
	{
		return $this->rencontreDAO->getGlobalStats();
	}
}
