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
    $api->deliverResponse('error', 404, 'Ressource inconnue.');
    exit;
}

// Décodage du corps de la requête
$requestBody = json_decode(file_get_contents('php://input'), true);
if (!is_array($requestBody)) {
    $api->deliverResponse('error', 400, 'Le corps de la requête est invalide.');
    exit;
}

try {
    $pdo = DBConnection::getInstance()->getConnection();
    $jwtSigner = new JWTSigner(JWT_PRIVATE_KEY_PATH);
    $auth = new Auth($pdo);

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

            $expiration = (new DateTime())->modify('+1 hour')->getTimestamp();
            $payload = ['email' => $email, 'exp' => $expiration];

            $jwt = $jwtSigner->generateJWT($payload);

            $api->deliverResponse('success', 200, 'Authentification réussie.', ['token' => $jwt]);
            exit;
        default:
            $api->deliverResponse('error', 404, 'Ressource inconnue.');
            exit;
    }
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, 'Connexion à la base de données impossible.');
} catch (Throwable $e) {
    error_log("Unexpected Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, 'Une erreur est survenue. Veuillez réessayer.');
}