<?php
// Routeur pour la gestion des joueurs

$isPositiveInt = $segment3 !== null && ctype_digit((string) $segment3) && (int) $segment3 > 0;
$joueurId = $isPositiveInt ? (int) $segment3 : null;

switch ($httpMethod) {
    case 'GET':
        if ($joueurId && $segment4 === 'commentaires') {
            $commentaires = (new ObtenirTousLesCommentairesDUnJoueur($joueurId))->executer();
            $data = array_map(fn($commentaire) => $mapper->commentaireToArray($commentaire), $commentaires);
            $api->deliverResponse('success', 200, 'OK', $data);
            exit;
        }

        if ($segment3 === 'moyennes-evaluations' && $segment4 === null) {
            $moyennes = (new ObtenirToutesLesMoyennesEvaluationJoueur())->executer();
            $api->deliverResponse('success', 200, 'OK', $moyennes);
            exit;
        }

        if ($joueurId && $segment4 === null) {
            $joueur = (new ObtenirUnJoueur($joueurId))->executer();
            $api->deliverResponse('success', 200, 'OK', $mapper->joueurToArray($joueur));
            exit;
        }

        if ($segment3 !== null || $segment4 !== null) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        $joueurs = (new ObtenirTousLesJoueurs())->executer();
        $data = array_map(fn($joueur) => $mapper->joueurToArray($joueur), $joueurs);
        $api->deliverResponse('success', 200, 'OK', $data);
        exit;
    case 'POST':
        if ($joueurId && $segment4 === 'commentaires') {
            $commentaire = $mapper->arrayToCommentaire($requestBody);
            (new CreerUnCommentaire($commentaire, $joueurId))->executer();
            $api->deliverResponse('success', 201, 'Commentaire ajouté avec succès.');
            exit;
        }

        if ($segment3 !== null || $segment4 !== null) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        $joueur = $mapper->arrayToJoueur($requestBody);
        (new CreerUnJoueur($joueur))->executer();
        $api->deliverResponse('success', 201, 'Joueur ajouté avec succès.');
        exit;
    case 'PUT':
        if ($segment3 === null) {
            $api->deliverResponse('error', 400, 'Identifiant manquant.');
            exit;
        }

        if (!$joueurId || $segment4 !== null) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        $joueur = $mapper->arrayToJoueur($requestBody);
        $joueur->setJoueurId($joueurId);
        (new ModifierUnJoueur($joueur))->executer();
        $api->deliverResponse('success', 200, 'Joueur modifié avec succès.');
        exit;
    case 'DELETE':
        if ($segment3 === null) {
            $api->deliverResponse('error', 400, 'Identifiant manquant.');
            exit;
        }

        if (!$joueurId || $segment4 !== null) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        (new SupprimerUnJoueur($joueurId))->executer();
        $api->deliverResponse('success', 200, 'Joueur supprimé avec succès.');
        exit;
    default:
        $api->deliverResponse('error', 405, 'Méthode non autorisée.');
        exit;
}
