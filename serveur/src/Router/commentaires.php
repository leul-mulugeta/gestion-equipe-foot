<?php
// Routeur pour la gestion des commentaires

$isPositiveInt = $segment3 !== null && ctype_digit((string) $segment3) && (int) $segment3 > 0;
$commentaireId = $isPositiveInt ? (int) $segment3 : null;

switch ($httpMethod) {
    case 'DELETE':
        if ($segment3 === null) {
            $api->deliverResponse('error', 400, 'Identifiant manquant.');
            exit;
        }

        if (!$commentaireId || $segment4 !== null) {
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
        }

        (new SupprimerUnCommentaire($commentaireId))->executer();
        $api->deliverResponse('success', 200, 'Commentaire supprimé avec succès.');
        exit;
    default:
        $api->deliverResponse('error', 405, 'Méthode non autorisée.');
        exit;
}
