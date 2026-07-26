<?php
// Chargement des dépendances et configuration de l'application

date_default_timezone_set('Europe/Paris');

// Configuration
require_once __DIR__ . '/config.php';

require_once __DIR__ . '/src/Api.php';
require_once __DIR__ . '/src/DBConnection.php';
require_once __DIR__ . '/src/BearerToken.php';
require_once __DIR__ . '/src/HttpClient.php';