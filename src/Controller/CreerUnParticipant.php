<?php

class CreerUnParticipant
{
	private ParticipantDAO $participantDAO;
	private int $id;
	private ?Joueur $joueur;
	private ?Rencontre $rencontre;
	private TypeDeParticipation $typeDeParticipation;
	private Poste $poste;
	private ?int $evaluation;

	public function __construct(int $id, ?Joueur $joueur, ?Rencontre $rencontre, TypeDeParticipation $typeDeParticipation, Poste $poste, ?int $evaluation)
	{
		$this->participantDAO = new ParticipantDAO();
		$this->id = $id;
		$this->joueur = $joueur;
		$this->rencontre = $rencontre;
		$this->typeDeParticipation = $typeDeParticipation;
		$this->poste = $poste;
		$this->evaluation = $evaluation;
	}

	public function executer(): ?Participant
	{
		$participant = new Participant($this->id, $this->joueur, $this->rencontre, $this->typeDeParticipation, $this->poste, $this->evaluation);
		return $this->participantDAO->insert($participant);
	}
}
