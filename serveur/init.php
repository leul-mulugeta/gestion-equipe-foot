<?php
// Chargement des dépendances et configuration de l'application

date_default_timezone_set('Europe/Paris');

// Configuration
require_once __DIR__ . '/config.php';

require_once __DIR__ . '/src/Api.php';
require_once __DIR__ . '/src/DBConnection.php';
require_once __DIR__ . '/src/BearerToken.php';
require_once __DIR__ . '/src/HttpClient.php';
require_once __DIR__ . '/src/Mapper.php';

// Énumérations
require_once __DIR__ . '/src/Model/Enum/Lieu.php';
require_once __DIR__ . '/src/Model/Enum/Poste.php';
require_once __DIR__ . '/src/Model/Enum/Resultat.php';
require_once __DIR__ . '/src/Model/Enum/Statut.php';
require_once __DIR__ . '/src/Model/Enum/TypeDeParticipation.php';

// Modèles
require_once __DIR__ . '/src/Model/Entity/Commentaire.php';
require_once __DIR__ . '/src/Model/Entity/Joueur.php';

// DAO
require_once __DIR__ . '/src/Model/DAO/CommentaireDAO.php';
require_once __DIR__ . '/src/Model/DAO/JoueurDAO.php';
require_once __DIR__ . '/src/Model/DAO/ParticipantDAO.php';

// Contrôleurs
require_once __DIR__ . '/src/Controller/CreerUnCommentaire.php';
require_once __DIR__ . '/src/Controller/ObtenirTousLesCommentairesDUnJoueur.php';

require_once __DIR__ . '/src/Controller/CreerUnJoueur.php';
require_once __DIR__ . '/src/Controller/ModifierUnJoueur.php';
require_once __DIR__ . '/src/Controller/ObtenirUnJoueur.php';
require_once __DIR__ . '/src/Controller/ObtenirTousLesJoueurs.php';
require_once __DIR__ . '/src/Controller/SupprimerUnJoueur.php';
require_once __DIR__ . '/src/Controller/ObtenirToutesLesMoyennesEvaluationJoueur.php';