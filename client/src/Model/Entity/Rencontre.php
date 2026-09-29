<?php

class Rencontre
{
	private int $rencontreId;
	private DateTime $dateEtHeure;
	private Lieu $lieu;
	private string $adresse;
	private string $nomEquipeAdverse;
	private ?int $scoreEquipeLocale;
	private ?int $scoreEquipeAdverse;

	public function __construct(int $rencontreId, DateTime $dateEtHeure, Lieu $lieu, string $adresse, string $nomEquipeAdverse, ?int $scoreEquipeLocale = null, ?int $scoreEquipeAdverse = null)
	{
		$adresse = trim($adresse);
		$nomEquipeAdverse = trim($nomEquipeAdverse);

		$this->rencontreId = $rencontreId;
		$this->dateEtHeure = $dateEtHeure;
		$this->lieu = $lieu;
		$this->adresse = $adresse;
		$this->nomEquipeAdverse = $nomEquipeAdverse;
		$this->scoreEquipeLocale = $scoreEquipeLocale;
		$this->scoreEquipeAdverse = $scoreEquipeAdverse;
	}

	public function getRencontreId(): int
	{
		return $this->rencontreId;
	}

	public function setRencontreId(int $rencontreId): void
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
		if ($this->scoreEquipeLocale === null || $this->scoreEquipeAdverse === null) {
			return null;
		}

		if ($this->scoreEquipeLocale === $this->scoreEquipeAdverse) {
			return Resultat::NUL;
		}

		$victoireLocale = $this->scoreEquipeLocale > $this->scoreEquipeAdverse;
		return $this->lieu === Lieu::DOMICILE
			? ($victoireLocale ? Resultat::VICTOIRE : Resultat::DEFAITE)
			: ($victoireLocale ? Resultat::DEFAITE : Resultat::VICTOIRE);
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
