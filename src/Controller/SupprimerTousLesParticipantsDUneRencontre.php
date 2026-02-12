<?php

class SupprimerTousLesParticipantsDUneRencontre
{
	private ParticipantDAO $participantDAO;
	private int $idRencontre;

	public function __construct(int $idRencontre)
	{
		$this->participantDAO = new ParticipantDAO();
		$this->idRencontre = $idRencontre;
	}

	public function executer(): bool
	{
		return $this->participantDAO->deleteAllByIdRencontre($this->idRencontre);
	}
}
