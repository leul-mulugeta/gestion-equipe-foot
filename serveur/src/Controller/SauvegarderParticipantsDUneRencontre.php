<?php

class SauvegarderParticipantsDUneRencontre
{
	private readonly ParticipantDAO $participantDAO;
	private readonly RencontreDAO $rencontreDAO;
	private readonly JoueurDAO $joueurDAO;
	private readonly int $rencontreId;
	private readonly array $participantsData;

	public function __construct(int $rencontreId, array $participantsData)
	{
		$this->participantDAO = ParticipantDAO::getInstance();
		$this->rencontreDAO = RencontreDAO::getInstance();
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->rencontreId = $rencontreId;
		$this->participantsData = $participantsData;
	}

	public function executer(): void
	{
		if (empty($this->participantsData)) {
			throw new InvalidArgumentException("Aucun participant n'a été fourni.");
		}

		$rencontre = $this->rencontreDAO->selectRencontreById($this->rencontreId);
		if ($rencontre->getDateEtHeure() < new DateTime()) {
			throw new ConflitException('La feuille de match ne peut plus être modifiée après la rencontre.');
		}

		$joueurIds = array_column($this->participantsData, 'joueurId');
		if (count($joueurIds) !== count(array_unique($joueurIds))) {
			throw new InvalidArgumentException('Un même joueur ne peut pas être inscrit deux fois à la même rencontre.');
		}

		$joueursEnBase = $this->joueurDAO->selectJoueursByIds($joueurIds);
		if (count($joueursEnBase) !== count($joueurIds)) {
			throw new InvalidArgumentException("Un ou plusieurs joueurs sélectionnés n'existent pas.");
		}

		$participants = [];
		$nbTitulaires = 0;
		$nbRemplacants = 0;
		$nbGardiensTitulaires = 0;

		foreach ($this->participantsData as $participantData) {
			$joueur = $joueursEnBase[$participantData['joueurId']];
			if ($joueur->getStatut() !== Statut::ACTIF) {
				throw new ConflitException("Le joueur {$joueur->getPrenom()} {$joueur->getNom()} est {$joueur->getStatut()->value} et ne peut pas être sélectionné.");
			}

			if ($participantData['typeDeParticipation'] === TypeDeParticipation::TITULAIRE) {
				$nbTitulaires++;
				if ($participantData['poste'] === Poste::GARDIEN) {
					$nbGardiensTitulaires++;
				}
			} else {
				$nbRemplacants++;
			}

			$participants[] = new Participant(
				0,
				$joueur,
				$this->rencontreId,
				$participantData['typeDeParticipation'],
				$participantData['poste']
			);
		}

		if ($nbTitulaires !== 11) {
			throw new InvalidArgumentException("Il faut exactement 11 titulaires (actuellement : $nbTitulaires).");
		}
		if ($nbGardiensTitulaires !== 1) {
			throw new InvalidArgumentException("Il faut exactement 1 gardien parmi les titulaires (actuellement : $nbGardiensTitulaires).");
		}
		if ($nbRemplacants > 7) {
			throw new InvalidArgumentException("Il ne peut y avoir plus de 7 remplaçants (actuellement : $nbRemplacants).");
		}

		$this->participantDAO->sauvegarderParticipants($this->rencontreId, $participants);
	}
}
