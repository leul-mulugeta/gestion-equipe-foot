<?php

class ModifierUnJoueur
{
	private readonly JoueurDAO $joueurDAO;
	private readonly Joueur $joueur;

	public function __construct(Joueur $joueur)
	{
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->joueur = $joueur;
	}

	public function executer(): void
	{
		$this->joueurDAO->selectJoueurById($this->joueur->getJoueurId());

		$this->joueurDAO->updateJoueur($this->joueur);
	}
}
