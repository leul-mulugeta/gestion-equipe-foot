<?php

class ModifierEvaluationsParticipants
{
	private readonly ParticipantDAO $participantDAO;
	private readonly RencontreDAO $rencontreDAO;
	private readonly int $rencontreId;
	private readonly array $evaluations;

	public function __construct(int $rencontreId, array $evaluations)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->rencontreId = $rencontreId;
		$this->evaluations = $evaluations;
	}

	public function executer(): void
	{
		$this->rencontreDAO->selectRencontreById($this->rencontreId);

		$this->participantDAO->updateEvaluationsParticipants($this->evaluations);
	}
}
