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

// Vérification de la structure attendue de l'URL (/serveur/...)
if (count($uriParts) < 3 || count($uriParts) > 5 || ($uriParts[1] ?? '') !== 'serveur') {
    $api->deliverResponse('error', 404, 'Ressource inconnue.');
    exit;
}

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

// Décodage du corps de la requête
$requestBody = json_decode(file_get_contents('php://input'), true);

try {
    $mapper = new Mapper();

    $resource = $uriParts[2];
    $segment3 = $uriParts[3] ?? null;
    $segment4 = $uriParts[4] ?? null;

    $routerFile = __DIR__ . "/../src/Router/$resource.php";
    if (file_exists($routerFile)) {
        require_once $routerFile;
    } else {
        $api->deliverResponse('error', 404, 'Ressource inconnue.');
        exit;
    }
} catch (InvalidArgumentException $e) {
	$api->deliverResponse('error', 400, $e->getMessage());
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, 'Connexion à la base de données impossible.');
} catch (RuntimeException $e) {
	$api->deliverResponse('error', 404, $e->getMessage());
} catch (Throwable $e) {
	error_log("Unexpected Error: " . $e->getMessage());
	$api->deliverResponse('error', 500, 'Une erreur serveur est survenue lors du traitement.');
}