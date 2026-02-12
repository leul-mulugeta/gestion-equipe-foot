<?php

class ObtenirTousLesParticipationsDUnJoueur
{
	private ParticipantDAO $participantDAO;
	private int $idJoueur;

	public function __construct(int $idJoueur)
	{
		$this->participantDAO = new ParticipantDAO();
		$this->idJoueur = $idJoueur;
	}

	public function executer(): array
	{
		return $this->participantDAO->selectAllByIdJoueur($this->idJoueur);
	}
}
