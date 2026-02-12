<?php

class ObtenirTousLesParticipantsDUneRencontre
{
	private ParticipantDAO $participantDAO;
	private int $idRencontre;

	public function __construct(int $idRencontre)
	{
		$this->participantDAO = new ParticipantDAO();
		$this->idRencontre = $idRencontre;
	}

	public function executer(): array
	{
		return $this->participantDAO->selectAllByIdRencontre($this->idRencontre);
	}
}
