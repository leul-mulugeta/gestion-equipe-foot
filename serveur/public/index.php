<?php
// Point d'entrée unique de l'API du serveur de données

require_once __DIR__ . '/../init.php';

$httpMethod = $_SERVER['REQUEST_METHOD'];

// Configuration du CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($httpMethod === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Analyse et nettoyage de l'URL
$path = strtok($_SERVER["REQUEST_URI"], '?');
$path = rtrim($path, '/');
$uriParts = explode('/', $path);

$api = new Api();

// Vérification de la structure attendue de l'URL
if (count($uriParts) < 2 || count($uriParts) > 4) {
    $api->deliverResponse('error', 404, 'Ressource inconnue.');
    exit;
}

// Vérification du token JWT
$bearerToken = new BearerToken();
$token = $bearerToken->getBearerToken();

if (!$token) {
    $api->deliverResponse('error', 401, 'Non authentifié.');
    exit;
}

try {
    $jwtVerifier = new JWTVerifier(JWT_PUBLIC_KEY_PATH);
} catch (RuntimeException $e) {
    error_log("JWT Config Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, "Une erreur est survenue. Veuillez réessayer.");
    exit;
}

if (!$jwtVerifier->isJWTValid($token)) {
    $api->deliverResponse('error', 401, 'Token invalide ou expiré.');
    exit;
}

// Décodage du corps de la requête
$rawBody = file_get_contents('php://input');
$requestBody = $rawBody !== '' ? json_decode($rawBody, true) : [];
if (!is_array($requestBody)) {
    $api->deliverResponse('error', 400, 'Le corps de la requête est invalide.');
    exit;
}

try {
    $mapper = new Mapper();

    $resource = $uriParts[1];
    $segment3 = $uriParts[2] ?? null;
    $segment4 = $uriParts[3] ?? null;

    $routerFile = __DIR__ . "/../src/Router/$resource.php";
    if (file_exists($routerFile)) {
        require_once $routerFile;
    } else {
        $api->deliverResponse('error', 404, 'Ressource inconnue.');
        exit;
    }
} catch (InvalidArgumentException $e) {
    $api->deliverResponse('error', 400, $e->getMessage());
} catch (ConflitException $e) {
    $api->deliverResponse('error', 409, $e->getMessage());
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, "Une erreur est survenue lors de l'accès aux données.");
} catch (RuntimeException $e) {
    $api->deliverResponse('error', 404, $e->getMessage());
} catch (Throwable $e) {
    error_log("Unexpected Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, 'Une erreur serveur est survenue lors du traitement.');
}