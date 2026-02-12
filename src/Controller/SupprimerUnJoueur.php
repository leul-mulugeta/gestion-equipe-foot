<?php

class SupprimerUnJoueur
{
	private JoueurDAO $joueurDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->joueurDAO = new JoueurDAO();
		$this->id = $id;
	}

	public function executer(): bool
	{
		return $this->joueurDAO->delete($this->id);
	}
}
