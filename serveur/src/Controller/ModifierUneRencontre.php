<?php

class ModifierUneRencontre
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
		$this->rencontreDAO->selectRencontreById($this->rencontre->getRencontreId());

		$this->rencontreDAO->updateRencontre($this->rencontre);
	}
}
