<?php

class ModifierEvaluationParticipant
{
	private readonly ParticipantDAO $participantDAO;
	private readonly int $participantId;
	private readonly int $evaluation;

	public function __construct(int $participantId, int $evaluation)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->participantId = $participantId;
		$this->evaluation = $evaluation;
	}

	public function executer(): bool
	{
		try {
			$this->participantDAO->updateEvaluationParticipant($this->participantId, $this->evaluation);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
