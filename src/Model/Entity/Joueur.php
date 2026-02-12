<?php

class Joueur
{
	private int $id;
	private int $numeroDeLicence;
	private string $nom;
	private string $prenom;
	private DateTime $dateDeNaissance;
	private int $taille;
	private float $poids;
	private Statut $statut;
	private Poste $poste;
	private array $commentaires;

	public function __construct(int $id, int $numeroDeLicence, string $nom, string $prenom, DateTime $dateDeNaissance, int $taille, float $poids, Statut $statut, Poste $poste)
	{
		$this->id = $id;
		$this->numeroDeLicence = $numeroDeLicence;
		$this->nom = $nom;
		$this->prenom = $prenom;
		$this->dateDeNaissance = $dateDeNaissance;
		$this->taille = $taille;
		$this->poids = $poids;
		$this->statut = $statut;
		$this->poste = $poste;
		$this->commentaires = [];
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId($id): void
	{
		$this->id = $id;
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

	public function getCommentaires(): array
	{
		return $this->commentaires;
	}

	public function addCommentaire(Commentaire $commentaire): void
	{
		$this->commentaires[] = $commentaire;
	}
}
