<?php

class SupprimerUneRencontre
{
	private readonly RencontreDAO $rencontreDAO;
	private readonly int $rencontreId;

	public function __construct(int $rencontreId)
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->rencontreId = $rencontreId;
	}

	public function executer(): void
	{
		$this->rencontreDAO->selectRencontreById($this->rencontreId);

		$this->rencontreDAO->deleteRencontre($this->rencontreId);
	}
}
