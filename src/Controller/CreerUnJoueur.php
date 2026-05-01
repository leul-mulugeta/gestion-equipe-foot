<?php

class CreerUnJoueur
{
	private readonly JoueurDAO $joueurDAO;
	private readonly Joueur $joueur;

	public function __construct(Joueur $joueur)
	{
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->joueur = $joueur;
	}

	public function executer(): bool
	{
		try {
			$this->joueurDAO->insertJoueur($this->joueur);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
