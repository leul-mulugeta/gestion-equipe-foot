<?php

class ObtenirToutesLesRencontres
{
	private RencontreDAO $rencontreDAO;

	public function __construct()
	{
		$this->rencontreDAO = new RencontreDAO();
	}

	public function executer(): array
	{
		return $this->rencontreDAO->selectAll();
	}
}
