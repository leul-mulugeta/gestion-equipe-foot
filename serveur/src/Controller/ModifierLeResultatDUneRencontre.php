<?php

class ModifierLeResultatDUneRencontre
{
	private readonly RencontreDAO $rencontreDAO;
	private readonly int $rencontreId;
	private readonly array $resultat;

	public function __construct(int $rencontreId, array $resultat)
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->rencontreId = $rencontreId;
		$this->resultat = $resultat;
	}

	public function executer(): void
	{
		$scoreEquipeLocale = $this->resultat['scoreEquipeLocale'] ?? null;
		$scoreEquipeAdverse = $this->resultat['scoreEquipeAdverse'] ?? null;

		if ($scoreEquipeLocale === null || $scoreEquipeAdverse === null) {
			throw new InvalidArgumentException('Les deux scores doivent être renseignés ensemble.');
		}

		foreach ([$scoreEquipeLocale, $scoreEquipeAdverse] as $score) {
			if (!is_int($score) || $score < 0 || $score > 99) {
				throw new InvalidArgumentException('Un score doit être un entier compris entre 0 et 99.');
			}
		}

		$rencontre = $this->rencontreDAO->selectRencontreById($this->rencontreId);
		if ($rencontre->getDateEtHeure() > new DateTime()) {
			throw new ConflitException("Impossible d'enregistrer le résultat d'une rencontre qui n'a pas encore été jouée.");
		}

		$this->rencontreDAO->updateScores($this->rencontreId, $scoreEquipeLocale, $scoreEquipeAdverse);
	}
}
