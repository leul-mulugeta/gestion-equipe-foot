<?php
// Point d'entrée unique de l'API d'authentification

require_once __DIR__ . '/../init.php';

$api = new Api();

try {
    $pdo = DBConnection::getInstance()->getConnection();
    $pdo->query("SELECT 1 FROM user LIMIT 1");
    
    $api->deliverResponse('success', 200, 'Connexion à la base de données ok.');
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $api->deliverResponse('error', 500, 'Connexion à la base de données impossible.');
}