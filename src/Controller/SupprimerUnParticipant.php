<?php

class SupprimerUnParticipant
{
	private ParticipantDAO $participantDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->participantDAO = new participantDAO();
		$this->id = $id;
	}

	public function executer(): bool
	{
		return $this->participantDAO->delete($this->id);
	}
}
