<?php

class SauvegarderParticipantsDUneRencontre
{
	private readonly ParticipantDAO $participantDAO;
	private readonly int $rencontreId;
	private readonly array $participants;

	public function __construct(int $rencontreId, array $participants)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->rencontreId = $rencontreId;
		$this->participants = $participants;
	}

	public function executer(): bool
	{
		return $this->participantDAO->sauvegarderParticipants($this->rencontreId, $this->participants);
	}
}
