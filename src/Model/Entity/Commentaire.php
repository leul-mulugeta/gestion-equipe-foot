<?php

class Commentaire
{
	private int $id;
	private ?Joueur $joueur;
	private string $contenu;

	public function __construct(int $id, ?Joueur $joueur, string $contenu)
	{
		$this->id = $id;
		$this->joueur = $joueur;
		$this->contenu = $contenu;
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

	public function getContenu(): string
	{
		return $this->contenu;
	}
}
