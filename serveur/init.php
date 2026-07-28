<?php
// Chargement des dépendances et configuration de l'application

date_default_timezone_set('Europe/Paris');

// Configuration
require_once __DIR__ . '/config.php';

require_once __DIR__ . '/src/Api.php';
require_once __DIR__ . '/src/DBConnection.php';
require_once __DIR__ . '/src/BearerToken.php';
require_once __DIR__ . '/src/HttpClient.php';

// Énumérations
require_once __DIR__ . '/src/Model/Enum/Lieu.php';
require_once __DIR__ . '/src/Model/Enum/Poste.php';
require_once __DIR__ . '/src/Model/Enum/Resultat.php';
require_once __DIR__ . '/src/Model/Enum/Statut.php';
require_once __DIR__ . '/src/Model/Enum/TypeDeParticipation.php';