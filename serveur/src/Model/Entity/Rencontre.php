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

	public function __construct(int $rencontreId, DateTime $dateEtHeure, Lieu $lieu, string $adresse, string $nomEquipeAdverse, ?Resultat $resultat = null, ?int $scoreEquipeLocale = null, ?int $scoreEquipeAdverse = null)
	{
		$annee = (int) $dateEtHeure->format('Y');
		if ($annee < 1900) {
			throw new InvalidArgumentException('La date de la rencontre ne peut pas être antérieure à 1900.');
		}
		if ($dateEtHeure > (new DateTime())->modify('+5 years')) {
			throw new InvalidArgumentException('La date de la rencontre ne peut pas dépasser 5 ans dans le futur.');
		}

		$adresse = trim($adresse);
		if ($adresse === '') {
			throw new InvalidArgumentException("L'adresse ne peut pas être vide.");
		}
		if (mb_strlen($adresse) > 100) {
			throw new InvalidArgumentException("L'adresse ne peut pas dépasser 100 caractères.");
		}

		$nomEquipeAdverse = trim($nomEquipeAdverse);
		if ($nomEquipeAdverse === '') {
			throw new InvalidArgumentException("Le nom de l'équipe adverse ne peut pas être vide.");
		}
		if (mb_strlen($nomEquipeAdverse) > 50) {
			throw new InvalidArgumentException("Le nom de l'équipe adverse ne peut pas dépasser 50 caractères.");
		}

		foreach ([$scoreEquipeLocale, $scoreEquipeAdverse] as $score) {
			if ($score !== null && ($score < 0 || $score > 99)) {
				throw new InvalidArgumentException('Un score doit être compris entre 0 et 99.');
			}
		}
		if (($scoreEquipeLocale === null) !== ($scoreEquipeAdverse === null)) {
			throw new InvalidArgumentException('Les deux scores doivent être renseignés ensemble.');
		}

		if ($resultat !== null && $scoreEquipeLocale === null) {
			throw new InvalidArgumentException('Un résultat ne peut pas être renseigné sans les scores.');
		}

		if ($dateEtHeure > new DateTime() && ($resultat !== null || $scoreEquipeLocale !== null)) {
			throw new InvalidArgumentException('Un match à venir ne peut pas avoir de résultat.');
		}

		if ($scoreEquipeLocale !== null && $scoreEquipeAdverse !== null && $resultat !== null) {
			if ($scoreEquipeLocale === $scoreEquipeAdverse) {
				$attendu = Resultat::NUL;
			} elseif ($lieu === Lieu::DOMICILE) {
				$attendu = $scoreEquipeLocale > $scoreEquipeAdverse ? Resultat::VICTOIRE : Resultat::DEFAITE;
			} else {
				$attendu = $scoreEquipeAdverse > $scoreEquipeLocale ? Resultat::VICTOIRE : Resultat::DEFAITE;
			}
			if ($resultat !== $attendu) {
				throw new InvalidArgumentException('Le résultat ne correspond pas aux scores.');
			}
		}

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
