<?php
// Point d'entrée unique de l'API d'authentification

require_once __DIR__ . '/../init.php';

$httpMethod = $_SERVER['REQUEST_METHOD'];

// Configuration du CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($httpMethod === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$api = new Api();

if ($httpMethod !== 'POST') {
    $api->deliverResponse('error', 405, 'Méthode non autorisée.');
    exit;
}

// Analyse et nettoyage de l'URL
$path = strtok($_SERVER["REQUEST_URI"], '?');
$path = rtrim($path, '/');
$uriParts = explode('/', $path);

// Vérification de la structure attendue de l'URL (/auth/...)
if (count($uriParts) !== 3 || ($uriParts[1] ?? '') !== 'auth') {
    $api->deliverResponse('error', 404, 'Ressource introuvable.');
    exit;
}

// Décodage du corps de la requête
$requestBody = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = DBConnection::getInstance()->getConnection();
    $jwtUtils = new JWTUtils(JWT_SECRET_KEY);
    $auth = new Auth($pdo, $jwtUtils);

    $endpoint = $uriParts[2];

    switch ($endpoint) {
        case 'login':
            $email = $requestBody['email'] ?? '';
            $password = $requestBody['password'] ?? '';

            if (empty($email) || empty($password)) {
                $api->deliverResponse('error', 400, 'Email et mot de passe obligatoires.');
                exit;
            }

            $loginSuccess = $auth->login($email, $password);
            if (!$loginSuccess) {
                $api->deliverResponse('error', 401, 'Email ou mot de passe incorrect.');
                exit;
            }

            // Préparation du Payload du JWT (expiration à 1h)
            $expiration = (new DateTime())->add(new DateInterval('PT1H'))->getTimestamp();
            $payload = ['email' => $email, 'exp' => $expiration];

            $jwt = $jwtUtils->generateJWT($payload);

            $api->deliverResponse('success', 200, 'Authentification réussie.', ['token' => $jwt]);
            exit;
        case 'verify':
            $jwt = $requestBody['jwt'] ?? '';
            $key = $requestBody['api_key'] ?? '';

            if (empty($jwt) || empty($key)) {
                $api->deliverResponse('error', 400, 'JWT et clé API obligatoires.');
                exit;
            }

            if (!hash_equals(INTERNAL_API_KEY, $key)) {
                $api->deliverResponse('error', 401, "Non autorisé.");
                exit;
            }

            $jwtValid = $auth->verify($jwt);
            if (!$jwtValid) {
                $api->deliverResponse('error', 401, 'Token invalide ou expiré.');
                exit;
            }

            $api->deliverResponse('success', 200, "Token valide.");
            exit;
        default:
            $api->deliverResponse('error', 404, 'Endpoint inconnu.');
            exit;
    }
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, 'Connexion à la base de données impossible.');
} catch (Throwable $e) {
    error_log("Unexpected Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, 'Une erreur est survenue. Veuillez réessayer.');
}