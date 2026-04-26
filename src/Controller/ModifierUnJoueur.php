<?php

class ModifierUnJoueur
{
	private JoueurDAO $joueurDAO;
	private int $id;
	private int $numeroDeLicence;
	private string $nom;
	private string $prenom;
	private DateTime $dateDeNaissance;
	private int $taille;
	private float $poids;
	private Statut $statut;
	private Poste $poste;

	public function __construct(int $id, int $numeroDeLicence, string $nom, string $prenom, DateTime $dateDeNaissance, int $taille, float $poids, Statut $statut, Poste $poste)
	{
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->id = $id;
		$this->numeroDeLicence = $numeroDeLicence;
		$this->nom = $nom;
		$this->prenom = $prenom;
		$this->dateDeNaissance = $dateDeNaissance;
		$this->taille = $taille;
		$this->poids = $poids;
		$this->statut = $statut;
		$this->poste = $poste;
	}

	public function executer(): bool
	{
		try {
			$joueur = new Joueur($this->id, $this->numeroDeLicence, $this->nom, $this->prenom, $this->dateDeNaissance, $this->taille, $this->poids, $this->statut, $this->poste);
			$this->joueurDAO->updateJoueur($joueur);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
