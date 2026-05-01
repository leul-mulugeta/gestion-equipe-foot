<?php

class ObtenirUnParticipant
{
	private readonly ParticipantDAO $participantDAO;
	private readonly int $participantId;

	public function __construct(int $participantId)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->participantId = $participantId;
	}

	public function executer(): ?Participant
	{
		return $this->participantDAO->selectParticipantById($this->participantId);
	}
}
