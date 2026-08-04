<?php

class SauvegarderParticipantsDUneRencontre
{
	private readonly ParticipantDAO $participantDAO;
	private readonly RencontreDAO $rencontreDAO;
	private readonly int $rencontreId;
	private readonly array $participants;

	public function __construct(int $rencontreId, array $participants)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->rencontreId = $rencontreId;
		$this->participants = $participants;
	}

	public function executer(): void
	{
		$this->rencontreDAO->selectRencontreById($this->rencontreId);

		foreach ($this->participants as $participant) {
			$participant->setRencontreId($this->rencontreId);
		}

		$this->participantDAO->sauvegarderParticipants($this->rencontreId, $this->participants);
	}
}
