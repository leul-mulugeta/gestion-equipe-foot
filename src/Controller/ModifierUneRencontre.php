<?php

class ModifierUneRencontre
{
	private RencontreDAO $rencontreDAO;
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
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->id = $id;
		$this->dateEtHeure = $dateEtHeure;
		$this->lieu = $lieu;
		$this->adresse = $adresse;
		$this->nomEquipeAdverse = $nomEquipeAdverse;
		$this->resultat = $resultat;
		$this->scoreEquipeLocale = $scoreEquipeLocale;
		$this->scoreEquipeAdverse = $scoreEquipeAdverse;
	}

	public function executer(): bool
	{
		try {
			$rencontre = new Rencontre($this->id, $this->dateEtHeure, $this->lieu, $this->adresse, $this->nomEquipeAdverse, $this->resultat, $this->scoreEquipeLocale, $this->scoreEquipeAdverse);
			$this->rencontreDAO->updateRencontre($rencontre);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
