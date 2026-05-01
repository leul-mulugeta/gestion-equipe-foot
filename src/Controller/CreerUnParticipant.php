<?php

class CreerUnParticipant
{
	private readonly ParticipantDAO $participantDAO;
	private readonly Participant $participant;

	public function __construct(Participant $participant)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->participant = $participant;
	}

	public function executer(): bool
	{
		try {
			$this->participantDAO->insertParticipant($this->participant);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
