<?php

class ModifierEvaluationParticipant
{
	private ParticipantDAO $participantDAO;
	private int $idParticipant;
	private ?int $evaluation;

	public function __construct(int $idParticipant, ?int $evaluation)
	{
		$this->participantDAO = new ParticipantDAO();
		$this->idParticipant = $idParticipant;
		$this->evaluation = $evaluation;
	}

	public function executer(): bool
	{
		return $this->participantDAO->updateEvaluation($this->idParticipant, $this->evaluation);
	}
}
