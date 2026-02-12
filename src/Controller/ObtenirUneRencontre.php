<?php

class ObtenirUneRencontre
{
	private RencontreDAO $rencontreDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->rencontreDAO = new RencontreDAO();
		$this->id = $id;
	}

	public function executer(): ?Rencontre
	{
		return $this->rencontreDAO->selectById($this->id);
	}
}
