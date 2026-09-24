<?php

class Mapper
{
	public static function arrayToJoueur(array $joueurData): Joueur
	{
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
}
