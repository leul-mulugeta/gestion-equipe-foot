<?php

class SupprimerTousLesParticipantsDUneRencontre
{
	private readonly ParticipantDAO $participantDAO;
	private readonly int $rencontreId;

	public function __construct(int $rencontreId)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->rencontreId = $rencontreId;
	}

	public function executer(): bool
	{
		try {
			$this->participantDAO->deleteParticipantsByRencontreId($this->rencontreId);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
