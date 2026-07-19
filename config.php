<?php

// L'opérateur "?:" permet d'utiliser les variables de Docker si elles existent, 
// sinon on utilise les valeurs par défaut (pour Laragon).

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'gestion-equipe-foot');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');

define('COACH_EMAIL', getenv('COACH_EMAIL') ?: 'coach@equipe.fr');
define('COACH_PASSWORD', getenv('COACH_PASSWORD') ?: 'motdepasse');
