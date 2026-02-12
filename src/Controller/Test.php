<?php

require_once __DIR__ . '/../init.php';

/*
$controleur = new CreerUnJoueur(
	0,
	'458701',
	'Doe',
	'John',
	'1990-01-01',
	'180',
	'75',
	Statut::ACTIF,
	Poste::ATTAQUANT
);
*/

$controleur = new SupprimerUnJoueur('22');
$joueur = $controleur->executer();
echo $joueur;
if ($joueur) {
	echo "true";
} else {
	echo "false";
}
