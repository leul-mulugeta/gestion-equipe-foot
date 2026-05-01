<?php

class ObtenirTousLesJoueurs
{
	private readonly JoueurDAO $joueurDAO;

	public function __construct()
	{
		$this->joueurDAO = JoueurDAO::getInstance();
	}

	public function executer(): array
	{
		return $this->joueurDAO->selectAllJoueurs();
	}
}
