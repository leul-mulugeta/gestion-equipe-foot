<?php

class ObtenirUnJoueur
{
	private JoueurDAO $joueurDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->id = $id;
	}

	public function executer(): ?Joueur
	{
		return $this->joueurDAO->selectJoueurById($this->id);
	}
}
