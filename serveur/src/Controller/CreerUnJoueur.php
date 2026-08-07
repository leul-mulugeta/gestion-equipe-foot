<?php

class CreerUnJoueur
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
		if ($this->joueurDAO->numeroLicenceExiste($this->joueur->getNumeroDeLicence())) {
			throw new ConflitException('Ce numéro de licence est déjà utilisé.');
		}

		$this->joueurDAO->insertJoueur($this->joueur);
	}
}
