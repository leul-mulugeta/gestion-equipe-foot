<?php

class ObtenirTousLesParticipantsDUneRencontre
{
	private readonly ParticipantDAO $participantDAO;
	private readonly int $rencontreId;

	public function __construct(int $rencontreId)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->rencontreId = $rencontreId;
	}

	public function executer(): array
	{
		return $this->participantDAO->selectParticipantsByRencontreId($this->rencontreId);
	}
}
