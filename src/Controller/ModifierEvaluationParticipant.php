<?php

class ModifierEvaluationParticipant
{
	private ParticipantDAO $participantDAO;
	private int $idParticipant;
	private int $evaluation;

	public function __construct(int $idParticipant, int $evaluation)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->idParticipant = $idParticipant;
		$this->evaluation = $evaluation;
	}

	public function executer(): bool
	{
		try {
			$this->participantDAO->updateEvaluationParticipant($this->idParticipant, $this->evaluation);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
