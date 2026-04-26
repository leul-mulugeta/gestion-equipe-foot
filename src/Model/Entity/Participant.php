<?php

class Participant
{
	private int $participantId;
	private Joueur $joueur;
	private int $rencontreId;
	private TypeDeParticipation $typeDeParticipation;
	private Poste $poste;
	private ?int $evaluation;

	public function __construct(int $participantId, Joueur $joueur, int $rencontreId, TypeDeParticipation $typeDeParticipation, Poste $poste, ?int $evaluation)
	{
		$this->participantId = $participantId;
		$this->joueur = $joueur;
		$this->rencontreId = $rencontreId;
		$this->typeDeParticipation = $typeDeParticipation;
		$this->poste = $poste;
		$this->evaluation = $evaluation;
	}

	public function getParticipantId(): int
	{
		return $this->participantId;
	}

	public function setParticipantId($participantId): void
	{
		$this->participantId = $participantId;
	}

	public function getJoueur(): Joueur
	{
		return $this->joueur;
	}

	public function getRencontreId(): int
	{
		return $this->rencontreId;
	}

	public function getTypeDeParticipation(): TypeDeParticipation
	{
		return $this->typeDeParticipation;
	}

	public function getPoste(): Poste
	{
		return $this->poste;
	}

	public function getEvaluation(): ?int
	{
		return $this->evaluation;
	}
}
