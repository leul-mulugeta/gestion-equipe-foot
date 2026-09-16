<?php
// Routeur pour la gestion des statistiques

switch ($httpMethod) {
    case 'GET':
        if ($segment4 !== null) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        if ($segment3 === 'globales') {
            $statsGlobales = (new ObtenirStatistiquesGlobales())->executer();
            $api->deliverResponse('success', 200, 'OK', $statsGlobales);
            exit;
        }

        if ($segment3 === 'joueurs') {
            $statsJoueurs = (new ObtenirStatistiquesJoueurs())->executer();
            $api->deliverResponse('success', 200, 'OK', $statsJoueurs);
            exit;
        }

        $api->deliverResponse('error', 404, 'Ressource inconnue.');
        exit;
    default:
        $api->deliverResponse('error', 405, 'Méthode non autorisée.');
        exit;
}
