<?php

class SupprimerUneRencontre
{
	private RencontreDAO $rencontreDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->id = $id;
	}

	public function executer(): bool
	{
		try {
			return $this->rencontreDAO->deleteRencontre($this->id);
		} catch (Exception $e) {
			return false;
		}
	}
}
