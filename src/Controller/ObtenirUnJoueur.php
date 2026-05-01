<?php

class ObtenirUnJoueur
{
	private readonly JoueurDAO $joueurDAO;
	private readonly int $joueurId;

	public function __construct(int $joueurId)
	{
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->joueurId = $joueurId;
	}

	public function executer(): ?Joueur
	{
		return $this->joueurDAO->selectJoueurById($this->joueurId);
	}
}
