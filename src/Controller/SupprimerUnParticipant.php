<?php

class SupprimerUnParticipant
{
	private readonly ParticipantDAO $participantDAO;
	private readonly int $participantId;

	public function __construct(int $participantId)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->participantId = $participantId;
	}

	public function executer(): bool
	{
		try {
			return $this->participantDAO->deleteParticipant($this->participantId);
		} catch (Exception $e) {
			return false;
		}
	}
}
