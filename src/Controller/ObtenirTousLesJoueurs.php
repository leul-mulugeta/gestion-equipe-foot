<?php

class ObtenirTousLesJoueurs
{
	private JoueurDAO $joueurDAO;

	public function __construct()
	{
		$this->joueurDAO = new JoueurDAO();
	}

	public function executer(): array
	{
		return $this->joueurDAO->selectAll();
	}
}
