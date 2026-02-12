<?php

class SupprimerUneRencontre
{
	private RencontreDAO $rencontreDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->rencontreDAO = new RencontreDAO();
		$this->id = $id;
	}

	public function executer(): bool
	{
		return $this->rencontreDAO->delete($this->id);
	}
}
