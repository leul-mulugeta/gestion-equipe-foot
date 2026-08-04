<?php

class ObtenirToutesLesRencontres
{
	private readonly RencontreDAO $rencontreDAO;

	public function __construct()
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
	}

	public function executer(): array
	{
		return $this->rencontreDAO->selectAllRencontres();
	}
}
