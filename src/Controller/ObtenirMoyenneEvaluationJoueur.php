<?php

class ObtenirMoyenneEvaluationJoueur
{
	private ParticipantDAO $participantDAO;
	private int $idJoueur;

	public function __construct(int $idJoueur)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->idJoueur = $idJoueur;
	}

	public function executer(): float
	{
		return $this->participantDAO->selectMoyennesEvaluationByJoueurId($this->idJoueur);
	}
}
