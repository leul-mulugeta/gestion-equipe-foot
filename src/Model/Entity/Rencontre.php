<?php

class Rencontre
{
	private int $id;
	private DateTime $dateEtHeure;
	private Lieu $lieu;
	private string $adresse;
	private string $nomEquipeAdverse;
	private ?Resultat $resultat;
	private ?int $scoreEquipeLocale;
	private ?int $scoreEquipeAdverse;

	public function __construct(int $id, DateTime $dateEtHeure, Lieu $lieu, string $adresse, string $nomEquipeAdverse, ?Resultat $resultat, ?int $scoreEquipeLocale, ?int $scoreEquipeAdverse)
	{
		$this->id = $id;
		$this->dateEtHeure = $dateEtHeure;
		$this->lieu = $lieu;
		$this->adresse = $adresse;
		$this->nomEquipeAdverse = $nomEquipeAdverse;
		$this->resultat = $resultat;
		$this->scoreEquipeLocale = $scoreEquipeLocale;
		$this->scoreEquipeAdverse = $scoreEquipeAdverse;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId($id): void
	{
		$this->id = $id;
	}

	public function getDateEtHeure(): DateTime
	{
		return $this->dateEtHeure;
	}

	public function getLieu(): Lieu
	{
		return $this->lieu;
	}

	public function getAdresse(): string
	{
		return $this->adresse;
	}

	public function getNomEquipeAdverse(): string
	{
		return $this->nomEquipeAdverse;
	}

	public function getResultat(): ?Resultat
	{
		return $this->resultat;
	}

	public function getScoreEquipeLocale(): ?int
	{
		return $this->scoreEquipeLocale;
	}

	public function getScoreEquipeAdverse(): ?int
	{
		return $this->scoreEquipeAdverse;
	}
}
