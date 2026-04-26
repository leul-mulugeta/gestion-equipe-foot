<?php

class ObtenirTousLesParticipantsDUneRencontre
{
	private ParticipantDAO $participantDAO;
	private int $idRencontre;

	public function __construct(int $idRencontre)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->idRencontre = $idRencontre;
	}

	public function executer(): array
	{
		return $this->participantDAO->selectParticipantsByRencontreId($this->idRencontre);
	}
}
