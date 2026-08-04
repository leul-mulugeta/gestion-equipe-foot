<?php

class CreerUneRencontre
{
	private readonly RencontreDAO $rencontreDAO;
	private readonly Rencontre $rencontre;

	public function __construct(Rencontre $rencontre)
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->rencontre = $rencontre;
	}

	public function executer(): void
	{
		$this->rencontreDAO->insertRencontre($this->rencontre);
	}
}
