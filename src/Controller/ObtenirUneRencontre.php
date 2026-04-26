<?php

class ObtenirUneRencontre
{
	private RencontreDAO $rencontreDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->id = $id;
	}

	public function executer(): ?Rencontre
	{
		return $this->rencontreDAO->selectRencontreById($this->id);
	}
}
