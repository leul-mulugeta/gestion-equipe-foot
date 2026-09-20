<?php

// L'opérateur "?:" permet d'utiliser les variables de Docker si elles existent, 
// sinon on utilise les valeurs par défaut (pour Laragon).

$prodConfig = __DIR__ . '/config.local.php';
if (file_exists($prodConfig)) {
    require $prodConfig;
} else {
    define('AUTH_URL', getenv('AUTH_URL') ?: throw new RuntimeException('AUTH_URL non défini.'));
    define('SERVEUR_URL', getenv('SERVEUR_URL') ?: throw new RuntimeException('SERVEUR_URL non défini.'));
}