<?php

class Joueur
{
	private int $joueurId;
	private int $numeroDeLicence;
	private string $nom;
	private string $prenom;
	private DateTime $dateDeNaissance;
	private int $taille;
	private float $poids;
	private Statut $statut;
	private Poste $poste;

	public function __construct(int $joueurId, int $numeroDeLicence, string $nom, string $prenom, DateTime $dateDeNaissance, int $taille, float $poids, Statut $statut, Poste $poste)
	{
		$this->joueurId = $joueurId;
		$this->numeroDeLicence = $numeroDeLicence;
		$this->nom = $nom;
		$this->prenom = $prenom;
		$this->dateDeNaissance = $dateDeNaissance;
		$this->taille = $taille;
		$this->poids = $poids;
		$this->statut = $statut;
		$this->poste = $poste;
	}

	public function getJoueurId(): int
	{
		return $this->joueurId;
	}

	public function setJoueurId($joueurId): void
	{
		$this->joueurId = $joueurId;
	}

	public function getNumeroDeLicence(): int
	{
		return $this->numeroDeLicence;
	}

	public function getNom(): string
	{
		return $this->nom;
	}

	public function getPrenom(): string
	{
		return $this->prenom;
	}

	public function getFullName(): string
	{
		return "{$this->prenom} {$this->nom}";
	}

	public function getDateDeNaissance(): DateTime
	{
		return $this->dateDeNaissance;
	}

	public function getTaille(): int
	{
		return $this->taille;
	}

	public function getPoids(): float
	{
		return $this->poids;
	}

	public function getStatut(): Statut
	{
		return $this->statut;
	}

	public function getPoste(): Poste
	{
		return $this->poste;
	}
}
