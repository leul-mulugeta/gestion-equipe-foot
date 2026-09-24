<?php

date_default_timezone_set('Europe/Paris');

require_once __DIR__ . '/config.php';

require_once __DIR__ . '/src/Api.php';
require_once __DIR__ . '/src/Mapper.php';

// Énumérations
require_once __DIR__ . '/src/Model/Enum/Poste.php';
require_once __DIR__ . '/src/Model/Enum/Statut.php';

// Entités
require_once __DIR__ . '/src/Model/Entity/Joueur.php';
require_once __DIR__ . '/src/Model/Entity/Commentaire.php';

require_once __DIR__ . '/src/Controller/SeConnecter.php';

// Joueurs
require_once __DIR__ . '/src/Controller/ObtenirTousLesJoueurs.php';
require_once __DIR__ . '/src/Controller/ObtenirUnJoueur.php';
require_once __DIR__ . '/src/Controller/CreerUnJoueur.php';

// Commentaires
require_once __DIR__ . '/src/Controller/ObtenirTousLesCommentairesDUnJoueur.php';
require_once __DIR__ . '/src/Controller/CreerUnCommentaire.php';
require_once __DIR__ . '/src/Controller/SupprimerUnCommentaire.php';
