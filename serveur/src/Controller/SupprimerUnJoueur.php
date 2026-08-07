<?php

class SupprimerUnJoueur
{
	private readonly JoueurDAO $joueurDAO;
	private readonly ParticipantDAO $participantDAO;
	private readonly int $joueurId;

	public function __construct(int $joueurId)
	{
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->joueurId = $joueurId;
	}

	public function executer(): void
	{
		$this->joueurDAO->selectJoueurById($this->joueurId);

		if ($this->participantDAO->joueurAParticipe($this->joueurId)) {
			throw new ConflitException('Ce joueur a participé à des rencontres et ne peut pas être supprimé.');
		}

		$this->joueurDAO->deleteJoueur($this->joueurId);
	}
}
