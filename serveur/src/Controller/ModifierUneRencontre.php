<?php

class ModifierUneRencontre
{
	private readonly RencontreDAO $rencontreDAO;
	private readonly Rencontre $rencontre;

	public function __construct(Rencontre $rencontre)
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->rencontre = $rencontre;
	}

	public function executer(): void
	{
		$rencontreEnBase = $this->rencontreDAO->selectRencontreById($this->rencontre->getRencontreId());
		if ($rencontreEnBase->getDateEtHeure() < new DateTime()) {
			throw new ConflitException('La planification ne peut plus être modifiée après la rencontre.');
		}

		if ($this->rencontre->getDateEtHeure() < new DateTime()) {
			throw new ConflitException('Une rencontre ne peut pas être replanifiée dans le passé.');
		}

		$this->rencontreDAO->updatePlanification($this->rencontre);
	}
}
