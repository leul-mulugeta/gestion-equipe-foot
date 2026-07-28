<?php

class ObtenirToutesLesMoyennesEvaluationJoueur
{
	private readonly ParticipantDAO $participantDAO;

	public function __construct()
	{
		$this->participantDAO = ParticipantDAO::getInstance();
	}

	public function executer(): array
	{
		return $this->participantDAO->selectMoyennesEvaluationByJoueur();
	}
}
