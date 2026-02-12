<?php

class Commentaire
{
	private int $id;
	private ?Joueur $joueur;
	private string $note;

	public function __construct(int $id, ?Joueur $joueur, string $note)
	{
		$this->id = $id;
		$this->joueur = $joueur;
		$this->note = $note;
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

	public function setJoueur($joueur): void
	{
		$this->joueur = $joueur;
	}

	public function getNote(): string
	{
		return $this->note;
	}
}
