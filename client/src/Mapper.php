<?php

class Mapper
{
	public static function joueurToArray(Joueur $joueur): array
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

	public static function arrayToJoueur(array $joueurData): Joueur
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
			throw new InvalidArgumentException('Tous les champs sont obligatoires : numeroDeLicence, nom, prenom, dateDeNaissance, taille, poids, statut, poste.');
		}
		try {
			return new Joueur(
				(int) ($joueurData['joueurId'] ?? 0),
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
			throw new InvalidArgumentException('Une ou plusieurs valeurs sont invalides (date, statut ou poste).');
		}
	}

	public static function commentaireToArray(Commentaire $commentaire): array
	{
		return [
			'commentaireId' => $commentaire->getCommentaireId(),
			'contenu' => $commentaire->getContenu()
		];
	}

	public static function arrayToCommentaire(array $commentaireData): Commentaire
	{
		if (!isset($commentaireData['contenu'])) {
			throw new InvalidArgumentException('Le contenu du commentaire est obligatoire.');
		}

		return new Commentaire(
			(int) ($commentaireData['commentaireId'] ?? 0),
			$commentaireData['contenu']
		);
	}

	public static function rencontreToArray(Rencontre $rencontre): array
	{
		return [
			'rencontreId' => $rencontre->getRencontreId(),
			'dateEtHeure' => $rencontre->getDateEtHeure()->format('Y-m-d H:i:s'),
			'lieu' => $rencontre->getLieu()->value,
			'adresse' => $rencontre->getAdresse(),
			'nomEquipeAdverse' => $rencontre->getNomEquipeAdverse()
		];
	}

	public static function arrayToRencontre(array $rencontreData): Rencontre
	{
		if (
			!isset(
			$rencontreData['dateEtHeure'],
			$rencontreData['lieu'],
			$rencontreData['adresse'],
			$rencontreData['nomEquipeAdverse']
		)
		) {
			throw new InvalidArgumentException('Tous les champs sont obligatoires : dateEtHeure, lieu, adresse, nomEquipeAdverse.');
		}
		try {
			return new Rencontre(
				(int) ($rencontreData['rencontreId'] ?? 0),
				new DateTime($rencontreData['dateEtHeure']),
				Lieu::from($rencontreData['lieu']),
				$rencontreData['adresse'],
				$rencontreData['nomEquipeAdverse'],
				$rencontreData['scoreEquipeLocale'] ?? null,
				$rencontreData['scoreEquipeAdverse'] ?? null
			);
		} catch (Throwable) {
			throw new InvalidArgumentException('Une ou plusieurs valeurs sont invalides (date ou lieu).');
		}
	}

	public static function arrayToParticipant(array $participantData): Participant
	{
		return new Participant(
			$participantData['participantId'],
			self::arrayToJoueur($participantData['joueur']),
			$participantData['rencontreId'],
			TypeDeParticipation::from($participantData['typeDeParticipation']),
			Poste::from($participantData['poste']),
			$participantData['evaluation'] ?? null
		);
	}
}
