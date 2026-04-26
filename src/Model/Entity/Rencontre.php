<?php

class Rencontre
{
	private int $rencontreId;
	private DateTime $dateEtHeure;
	private Lieu $lieu;
	private string $adresse;
	private string $nomEquipeAdverse;
	private ?Resultat $resultat;
	private ?int $scoreEquipeLocale;
	private ?int $scoreEquipeAdverse;

	public function __construct(int $rencontreId, DateTime $dateEtHeure, Lieu $lieu, string $adresse, string $nomEquipeAdverse, ?Resultat $resultat, ?int $scoreEquipeLocale, ?int $scoreEquipeAdverse)
	{
		$this->rencontreId = $rencontreId;
		$this->dateEtHeure = $dateEtHeure;
		$this->lieu = $lieu;
		$this->adresse = $adresse;
		$this->nomEquipeAdverse = $nomEquipeAdverse;
		$this->resultat = $resultat;
		$this->scoreEquipeLocale = $scoreEquipeLocale;
		$this->scoreEquipeAdverse = $scoreEquipeAdverse;
	}

	public function getRencontreId(): int
	{
		return $this->rencontreId;
	}

	public function setRencontreId($rencontreId): void
	{
		$this->rencontreId = $rencontreId;
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
