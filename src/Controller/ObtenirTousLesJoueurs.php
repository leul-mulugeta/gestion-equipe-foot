<?php

class ObtenirTousLesJoueurs
{
	private JoueurDAO $joueurDAO;

	public function __construct()
	{
		$this->joueurDAO = JoueurDAO::getInstance();
	}

	public function executer(): array
	{
		return $this->joueurDAO->selectAllJoueurs();
	}
}
