<?php

class ObtenirTousLesParticipantsDUneRencontre
{
	private readonly ParticipantDAO $participantDAO;
	private readonly RencontreDAO $rencontreDAO;
	private readonly int $rencontreId;

	public function __construct(int $rencontreId)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->rencontreId = $rencontreId;
	}

	public function executer(): array
	{
		$this->rencontreDAO->selectRencontreById($this->rencontreId);

		return $this->participantDAO->selectParticipantsByRencontreId($this->rencontreId);
	}
}
