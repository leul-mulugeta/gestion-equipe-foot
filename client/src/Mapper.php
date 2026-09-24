<?php

class Mapper
{
	public static function arrayToJoueur(array $joueurData): Joueur
	{
		return new Joueur(
			(int) $joueurData['joueurId'],
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

	public static function arrayToCommentaire(array $commentaireData): Commentaire
	{
		return new Commentaire(
			(int) $commentaireData['commentaireId'],
			$commentaireData['contenu']
		);
	}
}
