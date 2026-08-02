<?php

class ModifierUnJoueur
{
	private readonly JoueurDAO $joueurDAO;
	private readonly Joueur $joueur;

	public function __construct(Joueur $joueur)
	{
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->joueur = $joueur;
	}

	public function executer(): void
	{
		$this->joueurDAO->selectJoueurById($this->joueur->getJoueurId());

		if ($this->joueurDAO->numeroLicenceExiste($this->joueur->getNumeroDeLicence(), $this->joueur->getJoueurId())) {
			throw new RuntimeException('Ce numéro de licence est déjà utilisé.');
		}

		$this->joueurDAO->updateJoueur($this->joueur);
	}
}
