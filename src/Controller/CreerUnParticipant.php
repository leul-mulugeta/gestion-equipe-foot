<?php

class CreerUnParticipant
{
	private ParticipantDAO $participantDAO;
	private int $id;
	private Joueur $joueur;
	private int $rencontreId;
	private TypeDeParticipation $typeDeParticipation;
	private Poste $poste;
	private ?int $evaluation;

	public function __construct(int $id, Joueur $joueur, int $rencontreId, TypeDeParticipation $typeDeParticipation, Poste $poste, ?int $evaluation)
	{
		$this->participantDAO = new ParticipantDAO();
		$this->id = $id;
		$this->joueur = $joueur;
		$this->rencontreId = $rencontreId;
		$this->typeDeParticipation = $typeDeParticipation;
		$this->poste = $poste;
		$this->evaluation = $evaluation;
	}

	public function executer(): ?Participant
	{
		$participant = new Participant($this->id, $this->joueur, $this->rencontreId, $this->typeDeParticipation, $this->poste, $this->evaluation);
		return $this->participantDAO->insert($participant);
	}
}
