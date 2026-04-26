<?php

class SupprimerTousLesParticipantsDUneRencontre
{
	private ParticipantDAO $participantDAO;
	private int $idRencontre;

	public function __construct(int $idRencontre)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->idRencontre = $idRencontre;
	}

	public function executer(): bool
	{
		try {
			$this->participantDAO->deleteParticipantsByRencontreId($this->idRencontre);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
