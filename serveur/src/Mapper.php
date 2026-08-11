<?php
// Convertisseur entre données brutes et entités

class Mapper
{
	public function joueurToArray(Joueur $joueur): array
	{
		return [
			'joueurId' => $joueur->getJoueurId(),
			'numeroDeLicence' => $joueur->getNumeroDeLicence(),
			'nom' => $joueur->getNom(),
			'prenom' => $joueur->getPrenom(),
			'dateDeNaissance' => $joueur->getDateDeNaissance()->format('Y-m-d'),
			'taille' => $joueur->getTaille(),
			'poids' => $joueur->getPoids(),
			'statut' => $joueur->getStatut()->value,
			'poste' => $joueur->getPoste()->value
		];
	}

	public function arrayToJoueur(array $joueurData): Joueur
	{
		if (
			!isset(
			$joueurData['numeroDeLicence'],
			$joueurData['nom'],
			$joueurData['prenom'],
			$joueurData['dateDeNaissance'],
			$joueurData['taille'],
			$joueurData['poids'],
			$joueurData['statut'],
			$joueurData['poste']
		)
		) {
			throw new InvalidArgumentException("Tous les champs sont obligatoires : numeroDeLicence, nom, prenom, dateDeNaissance, taille, poids, statut, poste.");
		}
		try {
			return new Joueur(
				$joueurData['joueurId'] ?? 0,
				(int) $joueurData['numeroDeLicence'],
				$joueurData['nom'],
				$joueurData['prenom'],
				new DateTime($joueurData['dateDeNaissance']),
				(int) $joueurData['taille'],
				(float) $joueurData['poids'],
				Statut::from($joueurData['statut']),
				Poste::from($joueurData['poste'])
			);
		} catch (Throwable) {
			throw new InvalidArgumentException("Une ou plusieurs valeurs sont invalides.");
		}
	}

	public function commentaireToArray(Commentaire $commentaire): array
	{
		return [
			'commentaireId' => $commentaire->getCommentaireId(),
			'contenu' => $commentaire->getContenu()
		];
	}

	public function arrayToCommentaire(array $commentaireData): Commentaire
	{
		if (!isset($commentaireData['contenu'])) {
			throw new InvalidArgumentException("Tous les champs sont obligatoires : contenu.");
		}
		try {
			return new Commentaire(
				0,
				$commentaireData['contenu']
			);
		} catch (Throwable) {
			throw new InvalidArgumentException("Une ou plusieurs valeurs sont invalides.");
		}
	}

	public function rencontreToArray(Rencontre $rencontre): array
	{
		return [
			'rencontreId' => $rencontre->getRencontreId(),
			'dateEtHeure' => $rencontre->getDateEtHeure()->format('Y-m-d H:i:s'),
			'lieu' => $rencontre->getLieu()->value,
			'adresse' => $rencontre->getAdresse(),
			'nomEquipeAdverse' => $rencontre->getNomEquipeAdverse(),
			'resultat' => $rencontre->getResultat()?->value,
			'scoreEquipeLocale' => $rencontre->getScoreEquipeLocale(),
			'scoreEquipeAdverse' => $rencontre->getScoreEquipeAdverse()
		];
	}

	public function arrayToRencontre(array $rencontreData): Rencontre
	{
		if (
			!isset(
			$rencontreData['dateEtHeure'],
			$rencontreData['lieu'],
			$rencontreData['adresse'],
			$rencontreData['nomEquipeAdverse']
		)
		) {
			throw new InvalidArgumentException("Tous les champs sont obligatoires : dateEtHeure, lieu, adresse, nomEquipeAdverse.");
		}
		try {
			return new Rencontre(
				$rencontreData['rencontreId'] ?? 0,
				new DateTime($rencontreData['dateEtHeure']),
				Lieu::from($rencontreData['lieu']),
				$rencontreData['adresse'],
				$rencontreData['nomEquipeAdverse']
			);
		} catch (Throwable) {
			throw new InvalidArgumentException("Une ou plusieurs valeurs sont invalides.");
		}
	}

	public function participantToArray(Participant $participant): array
	{
		return [
			'participantId' => $participant->getParticipantId(),
			'joueur' => $this->joueurToArray($participant->getJoueur()),
			'rencontreId' => $participant->getRencontreId(),
			'typeDeParticipation' => $participant->getTypeDeParticipation()->value,
			'poste' => $participant->getPoste()->value,
			'evaluation' => $participant->getEvaluation()
		];
	}

	// Une feuille de match est une liste d'objets, un par participant
	public function arrayToParticipantsData(array $requestBody): array
	{
		$participantsData = [];

		foreach ($requestBody as $participantData) {
			if (!is_array($participantData)) {
				throw new InvalidArgumentException('Chaque participant doit être un objet JSON.');
			}

			$participantsData[] = $this->arrayToParticipantData($participantData);
		}

		return $participantsData;
	}

	private function arrayToParticipantData(array $participantData): array
	{
		if (
			!isset(
			$participantData['joueurId'],
			$participantData['typeDeParticipation'],
			$participantData['poste']
		)
		) {
			throw new InvalidArgumentException("Tous les champs sont obligatoires : joueurId, typeDeParticipation, poste.");
		}
		try {
			return [
				'joueurId' => (int) $participantData['joueurId'],
				'typeDeParticipation' => TypeDeParticipation::from($participantData['typeDeParticipation']),
				'poste' => Poste::from($participantData['poste'])
			];
		} catch (Throwable) {
			throw new InvalidArgumentException("Une ou plusieurs valeurs sont invalides.");
		}
	}
}
