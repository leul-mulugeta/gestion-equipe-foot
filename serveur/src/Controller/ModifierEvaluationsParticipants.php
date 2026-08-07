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
		if (empty($this->evaluations)) {
			throw new InvalidArgumentException("Aucune évaluation n'a été fournie.");
		}

		foreach ($this->evaluations as $evaluation) {
			if (!is_int($evaluation) || $evaluation < 1 || $evaluation > 5) {
				throw new InvalidArgumentException("L'évaluation doit être comprise entre 1 et 5.");
			}
		}

		$rencontre = $this->rencontreDAO->selectRencontreById($this->rencontreId);
		if ($rencontre->getDateEtHeure() > new DateTime()) {
			throw new ConflitException("Impossible d'évaluer les joueurs d'un match qui n'a pas encore été joué.");
		}

		$participants = $this->participantDAO->selectParticipantsByRencontreId($this->rencontreId);
		if (empty($participants)) {
			throw new ConflitException("Aucune feuille de match n'a été saisie avant la rencontre.");
		}

		$idsValides = array_map(fn($p) => $p->getParticipantId(), $participants);
		foreach (array_keys($this->evaluations) as $participantId) {
			if (!in_array($participantId, $idsValides, true)) {
				throw new ConflitException('Un participant ne fait pas partie de cette rencontre.');
			}
		}

		$this->participantDAO->updateEvaluationsParticipants($this->rencontreId, $this->evaluations);
	}
}
