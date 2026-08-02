<?php
// Routeur pour la gestion des commentaires

$commentaireId = $segment3 ? (int) $segment3 : null;

switch ($httpMethod) {
    case 'DELETE':
        if ($segment4) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        if (!$commentaireId) {
            $api->deliverResponse('error', 400, 'Identifiant manquant.');
            exit;
        }

        (new SupprimerUnCommentaire($commentaireId))->executer();
        $api->deliverResponse('success', 200, 'Commentaire supprimé avec succès.');
        exit;
    default:
        $api->deliverResponse('error', 405, 'Méthode non autorisée.');
        exit;
}
