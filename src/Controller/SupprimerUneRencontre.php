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

	public function executer(): bool
	{
		try {
			return $this->rencontreDAO->deleteRencontre($this->rencontreId);
		} catch (Exception $e) {
			return false;
		}
	}
}
