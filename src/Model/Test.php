<?php

require_once __DIR__ . '/../init.php';

$joueur = new Joueur(
	0,
	'4587',
	'Doe',
	'John',
	'1990-01-01',
	'180',
	'75',
	Statut::ACTIF,
	Poste::ATTAQUANT
);

/*
$joueurDAO = new JoueurDAO();
$result = $joueurDAO->insert($joueur);

if ($result) {
	echo "Joueur inséré avec succès.";
} else {
	echo "Échec de l'insertion du joueur.";
}
*/
/*
$joueurDAO = new JoueurDAO();
$id = '15';
$result = $joueurDAO->delete($id);
if ($result) {
	echo "Le joueur avec le numéro de licence $id a été supprimé avec succès.";
} else {
	echo "Le joueur avec le numéro de licence $id n'existe pas.";
}
*/


$commentaireDAO = new CommentaireDAO();
// $commentaire = new Commentaire(0, $joueur, "Excellent joueur");
// $result = $commentaireDAO->insert($commentaire);

$result = $commentaireDAO->selectByIdJoueur(13);

foreach ($result as $commentaire) {
	echo "Commentaire ID: " . $commentaire->getId() . " Joueur: " . $commentaire->getJoueur() . " Note: " . $commentaire->getNote() . "<br>";
}

if ($result) {
	echo "Commentaire inséré avec succès.";
} else {
	echo "Échec de l'insertion du commentaire.";
}


/*
$joueurDAO = new JoueurDAO();
$joueur = $joueurDAO->selectById('13');

if ($joueur) {
	# Echo tout les informations du joueur
	echo "ID: " . $joueur->getId() . "Numero de Licence: " . $joueur->getNumeroDeLicence() . "Nom: " . $joueur->getNom() . "Prenom: " . $joueur->getPrenom() . "Date de Naissance: " . $joueur->getDateDeNaissance() . "Taille: " . $joueur->getTaille() . "Poids: " . $joueur->getPoids() . "Statut: " . $joueur->getStatut()->name . "Poste: " . $joueur->getPoste()->name . "<br>";
	# Echo les commentaires du joueur
	$commentaires = $joueur->getCommentaires();
	foreach ($commentaires as $commentaire) {
		echo "Commentaire ID: " . $commentaire->getId() . " Note: " . $commentaire->getNote() . "<br>";
	}
} else {
	echo "Joueur non trouvé.<br>";
}
*/


$rencontreDAO = new RencontreDAO();

/*
$match1 = new Rencontre(
	0,
	'2024-10-15 18:00:00',
	Lieu::DOMICILE,
	'123 Rue du Stade, Ville',
	'Equipe Adverse A',
	Resultat::VICTOIRE,
	3,
	1
);

$rencontreDAO->insert($match1);

$match2 = new Rencontre(
	0,
	'2024-11-20 20:00:00',
	Lieu::EXTERIEUR,
	'456 Avenue des Sports, Autre Ville',
	'Equipe Adverse B',
	Resultat::DEFAITE,
	0,
	2
);

$rencontreDAO->insert($match2);
*/

/*
$rencontres = $rencontreDAO->selectAll();
foreach ($rencontres as $rencontre) {
	echo "ID: " . $rencontre->getId() . " Date et Heure: " . $rencontre->getDateEtHeure() . " Lieu: " . $rencontre->getLieu()->name . " Adresse: " . $rencontre->getAdresse() . " Nom Equipe Adverse: " . $rencontre->getNomEquipeAdverse() . " Resultat: " . $rencontre->getResultat()->name . " Score Equipe Locale: " . $rencontre->getScoreEquipeLocale() . " Score Equipe Adverse: " . $rencontre->getScoreEquipeAdverse() . "<br>";
}
*/