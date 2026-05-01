<?php

class ObtenirMoyenneEvaluationJoueur
{
	private readonly ParticipantDAO $participantDAO;
	private readonly int $joueurId;

	public function __construct(int $joueurId)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->joueurId = $joueurId;
	}

	public function executer(): float
	{
		return $this->participantDAO->selectMoyennesEvaluationByJoueurId($this->joueurId);
	}
}
