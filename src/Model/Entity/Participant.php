<?php

class Participant
{
	private int $id;
	private ?Joueur $joueur;
	private ?Rencontre $rencontre;
	private TypeDeParticipation $typeDeParticipation;
	private Poste $poste;
	private ?int $evaluation;

	public function __construct(int $id, ?Joueur $joueur, ?Rencontre $rencontre, TypeDeParticipation $typeDeParticipation, Poste $poste, ?int $evaluation)
	{
		$this->id = $id;
		$this->joueur = $joueur;
		$this->rencontre = $rencontre;
		$this->typeDeParticipation = $typeDeParticipation;
		$this->poste = $poste;
		$this->evaluation = $evaluation;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId($id): void
	{
		$this->id = $id;
	}

	public function getJoueur(): Joueur
	{
		return $this->joueur;
	}

	public function setJoueur(Joueur $joueur): void
	{
		$this->joueur = $joueur;
	}

	public function getRencontre(): Rencontre
	{
		return $this->rencontre;
	}

	public function setRencontre($rencontre): void
	{
		$this->rencontre = $rencontre;
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
