<?php

class ObtenirUneRencontre
{
	private readonly RencontreDAO $rencontreDAO;
	private readonly int $rencontreId;

	public function __construct(int $rencontreId)
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->rencontreId = $rencontreId;
	}

	public function executer(): ?Rencontre
	{
		return $this->rencontreDAO->selectRencontreById($this->rencontreId);
	}
}
