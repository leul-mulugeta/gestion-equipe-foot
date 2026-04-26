<?php

class SupprimerUnJoueur
{
	private JoueurDAO $joueurDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->id = $id;
	}

	public function executer(): bool
	{
		try {
			return $this->joueurDAO->deleteJoueur($this->id);
		} catch (Exception $e) {
			return false;
		}
	}
}
