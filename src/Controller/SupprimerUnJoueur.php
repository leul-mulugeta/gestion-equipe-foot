<?php

class SupprimerUnJoueur
{
	private readonly JoueurDAO $joueurDAO;
	private readonly int $joueurId;

	public function __construct(int $joueurId)
	{
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->joueurId = $joueurId;
	}

	public function executer(): bool
	{
		try {
			return $this->joueurDAO->deleteJoueur($this->joueurId);
		} catch (Exception $e) {
			return false;
		}
	}
}
