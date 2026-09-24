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
		if ($numeroDeLicence <= 0) {
			throw new InvalidArgumentException('Le numéro de licence doit être un entier positif.');
		}

		$nom = trim($nom);
		if ($nom === '') {
			throw new InvalidArgumentException('Le nom ne peut pas être vide.');
		}
		if (mb_strlen($nom) > 50) {
			throw new InvalidArgumentException('Le nom ne peut pas dépasser 50 caractères.');
		}

		$prenom = trim($prenom);
		if ($prenom === '') {
			throw new InvalidArgumentException('Le prénom ne peut pas être vide.');
		}
		if (mb_strlen($prenom) > 50) {
			throw new InvalidArgumentException('Le prénom ne peut pas dépasser 50 caractères.');
		}

		$age = $dateDeNaissance->diff(new DateTime())->y;
		if ($dateDeNaissance > new DateTime()) {
			throw new InvalidArgumentException('La date de naissance ne peut pas être dans le futur.');
		}
		if ($age < 5 || $age > 100) {
			throw new InvalidArgumentException("L'âge doit être compris entre 5 et 100 ans.");
		}

		if ($taille < 100 || $taille > 250) {
			throw new InvalidArgumentException('La taille doit être comprise entre 100 et 250 cm.');
		}

		if ($poids < 20 || $poids > 200) {
			throw new InvalidArgumentException('Le poids doit être compris entre 20 et 200 kg.');
		}

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

	public function setJoueurId(int $joueurId): void
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
