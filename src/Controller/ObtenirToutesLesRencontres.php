<?php

class ObtenirToutesLesRencontres
{
	private RencontreDAO $rencontreDAO;

	public function __construct()
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
	}

	public function executer(): array
	{
		return $this->rencontreDAO->selectAllRencontres();
	}
}
