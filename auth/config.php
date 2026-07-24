<?php

// L'opérateur "?:" permet d'utiliser les variables de Docker si elles existent, 
// sinon on utilise les valeurs par défaut (pour Laragon).

$prodConfig = __DIR__ . '/config.local.php';
if (file_exists($prodConfig)) {
    require $prodConfig;
} else {
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    define('DB_NAME', getenv('DB_NAME') ?: 'auth_db');
    define('DB_USER', getenv('DB_USER') ?: 'root');
    define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
    define('JWT_SECRET_KEY', getenv('JWT_SECRET_KEY') ?: 'cle_secrete');
    define('INTERNAL_API_KEY', getenv('INTERNAL_API_KEY') ?: 'cle_api');
}