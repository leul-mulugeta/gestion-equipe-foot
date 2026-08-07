<?php

class CreerUneRencontre
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
		if ($this->rencontre->getDateEtHeure() < new DateTime()) {
			throw new ConflitException('Une rencontre ne peut pas être créée dans le passé.');
		}

		$this->rencontreDAO->insertRencontre($this->rencontre);
	}
}
