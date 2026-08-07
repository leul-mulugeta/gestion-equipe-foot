<?php
// Routeur pour la gestion des rencontres

$rencontreId = $segment3 ? (int) $segment3 : null;

switch ($httpMethod) {
    case 'GET':
        if ($rencontreId && $segment4 === 'participants') {
            $participants = (new ObtenirTousLesParticipantsDUneRencontre($rencontreId))->executer();
            $data = array_map(fn($participant) => $mapper->participantToArray($participant), $participants);
            $api->deliverResponse('success', 200, 'OK', $data);
            exit;
        }

        if ($rencontreId && !$segment4) {
            $rencontre = (new ObtenirUneRencontre($rencontreId))->executer();
            $api->deliverResponse('success', 200, 'OK', $mapper->rencontreToArray($rencontre));
            exit;
        }

        if ($segment4) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        $rencontres = (new ObtenirToutesLesRencontres())->executer();
        $data = array_map(fn($rencontre) => $mapper->rencontreToArray($rencontre), $rencontres);
        $api->deliverResponse('success', 200, 'OK', $data);
        exit;
    case 'POST':
        if ($segment3 || $segment4) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        $rencontre = $mapper->arrayToRencontre($requestBody);
        (new CreerUneRencontre($rencontre))->executer();
        $api->deliverResponse('success', 201, 'Rencontre ajoutée avec succès.');
        exit;
    case 'PUT':
        if ($rencontreId && $segment4 === 'participants') {
            $participants = array_map(fn($participant) => $mapper->arrayToParticipant($participant), $requestBody);
            (new SauvegarderParticipantsDUneRencontre($rencontreId, $participants))->executer();
            $api->deliverResponse('success', 200, 'Participants sauvegardés avec succès.');
            exit;
        }

        if (!$rencontreId) {
            $api->deliverResponse('error', 400, 'Identifiant manquant.');
            exit;
        }

        if ($segment4) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        $rencontre = $mapper->arrayToRencontre($requestBody);
        $rencontre->setRencontreId($rencontreId);
        (new ModifierUneRencontre($rencontre))->executer();
        $api->deliverResponse('success', 200, 'Rencontre modifiée avec succès.');
        exit;
    case 'PATCH':
        if ($rencontreId && $segment4 === 'evaluations') {
            (new ModifierEvaluationsParticipants($rencontreId, $requestBody))->executer();
            $api->deliverResponse('success', 200, 'Évaluations des participants modifiées avec succès.');
            exit;
        }

        if ($rencontreId && $segment4 === 'resultat') {
            (new ModifierLeResultatDUneRencontre($rencontreId, $requestBody))->executer();
            $api->deliverResponse('success', 200, 'Résultat de la rencontre modifié avec succès.');
            exit;
        }

        if (!$rencontreId) {
            $api->deliverResponse('error', 400, 'Identifiant manquant.');
            exit;
        }

        $api->deliverResponse('error', 404, 'Ressource inconnue.');
        exit;
    case 'DELETE':
        if (!$rencontreId) {
            $api->deliverResponse('error', 400, 'Identifiant manquant.');
            exit;
        }

        if ($segment4) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        (new SupprimerUneRencontre($rencontreId))->executer();
        $api->deliverResponse('success', 200, 'Rencontre supprimée avec succès.');
        exit;
    default:
        $api->deliverResponse('error', 405, 'Méthode non autorisée.');
        exit;
}
