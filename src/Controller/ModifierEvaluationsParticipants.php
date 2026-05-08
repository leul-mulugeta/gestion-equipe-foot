<?php

class ModifierEvaluationsParticipants
{
	private readonly ParticipantDAO $participantDAO;
	private readonly array $evaluations;

	public function __construct(array $evaluations)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->evaluations = $evaluations;
	}

	public function executer(): bool
	{
		return $this->participantDAO->updateEvaluationsParticipants($this->evaluations);
	}
}
