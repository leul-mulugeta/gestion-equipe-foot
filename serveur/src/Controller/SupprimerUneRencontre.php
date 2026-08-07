<?php

class SupprimerUneRencontre
{
	private readonly RencontreDAO $rencontreDAO;
	private readonly int $rencontreId;

	public function __construct(int $rencontreId)
	{
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->rencontreId = $rencontreId;
	}

	public function executer(): void
	{
		$rencontre = $this->rencontreDAO->selectRencontreById($this->rencontreId);
		if ($rencontre->getDateEtHeure() < new DateTime()) {
			throw new ConflitException('Une rencontre déjà jouée ne peut pas être supprimée.');
		}

		$this->rencontreDAO->deleteRencontre($this->rencontreId);
	}
}
