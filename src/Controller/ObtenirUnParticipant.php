<?php

class ObtenirUnParticipant
{
	private ParticipantDAO $participantDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->id = $id;
	}

	public function executer(): ?Participant
	{
		return $this->participantDAO->selectParticipantById($this->id);
	}
}
