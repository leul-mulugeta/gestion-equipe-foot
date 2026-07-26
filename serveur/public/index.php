<?php
// Point d'entrée unique de l'API du serveur de données

require_once __DIR__ . '/../init.php';

$api = new Api();

// Vérification du token JWT auprès du serveur d'authentification
$bearerToken = new BearerToken();
$token = $bearerToken->getBearerToken();

if (!$token) {
    $api->deliverResponse('error', 401, 'Non authentifié.');
    exit;
}

$httpClient = new HttpClient(AUTH_URL . '/auth/verify');

try {
    $response = $httpClient->post(['jwt' => $token, 'api_key' => INTERNAL_API_KEY]);
} catch (RuntimeException $e) {
    error_log("Auth Service Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, "Une erreur est survenue. Veuillez réessayer.");
    exit;
}

if (!$response) {
    error_log("Auth Service Error: réponse invalide (non-JSON)");
    $api->deliverResponse('error', 500, "Une erreur est survenue. Veuillez réessayer.");
    exit;
}

if ($response['status_code'] !== 200) {
    $api->deliverResponse('error', $response['status_code'], $response['status_message']);
    exit;
}

try {
    $pdo = DBConnection::getInstance()->getConnection();
    $pdo->query("SELECT 1 FROM joueur LIMIT 1");

    $api->deliverResponse('success', 200, 'Connexion à la base de données ok.');
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, 'Connexion à la base de données impossible.');
}