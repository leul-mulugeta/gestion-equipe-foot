<?php

class SupprimerUnParticipant
{
	private ParticipantDAO $participantDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->id = $id;
	}

	public function executer(): bool
	{
		try {
			return $this->participantDAO->deleteParticipant($this->id);
		} catch (Exception $e) {
			return false;
		}
	}
}
