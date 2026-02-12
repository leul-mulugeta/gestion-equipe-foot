<?php

class ObtenirMoyenneEvaluationJoueur
{
	private ParticipantDAO $participantDAO;
	private int $idJoueur;

	public function __construct(int $idJoueur)
	{
		$this->participantDAO = new ParticipantDAO();
		$this->idJoueur = $idJoueur;
	}

	public function executer(): float
	{
		return $this->participantDAO->getMoyenneEvaluationByIdJoueur($this->idJoueur);
	}
}
