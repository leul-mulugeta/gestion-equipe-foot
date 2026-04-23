<?php

// Charger la configuration
require_once __DIR__ . '/config.php';

// Enums
require_once __DIR__ . '/src/Model/Enum/Lieu.php';
require_once __DIR__ . '/src/Model/Enum/Poste.php';
require_once __DIR__ . '/src/Model/Enum/Resultat.php';
require_once __DIR__ . '/src/Model/Enum/Statut.php';
require_once __DIR__ . '/src/Model/Enum/TypeDeParticipation.php';

// Modèles
require_once __DIR__ . '/src/Model/Entity/Commentaire.php';
require_once __DIR__ . '/src/Model/Entity/Joueur.php';
require_once __DIR__ . '/src/Model/Entity/Rencontre.php';
require_once __DIR__ . '/src/Model/Entity/Participant.php';

// DAO
require_once __DIR__ . '/src/Model/DAO/MySQLDataSource.php';
require_once __DIR__ . '/src/Model/DAO/CommentaireDAO.php';
require_once __DIR__ . '/src/Model/DAO/JoueurDAO.php';
require_once __DIR__ . '/src/Model/DAO/RencontreDAO.php';
require_once __DIR__ . '/src/Model/DAO/ParticipantDAO.php';
require_once __DIR__ . '/src/Model/DAO/StatistiquesDAO.php';

// Controleur
require_once __DIR__ . '/src/Controller/SeConnecter.php';

require_once __DIR__ . '/src/Controller/CreerUnCommentaire.php';
require_once __DIR__ . '/src/Controller/ObtenirToutLesCommentairesDUnJoueur.php';
require_once __DIR__ . '/src/Controller/SupprimerUnCommentaire.php';

require_once __DIR__ . '/src/Controller/CreerUnJoueur.php';
require_once __DIR__ . '/src/Controller/ModifierUnJoueur.php';
require_once __DIR__ . '/src/Controller/ObtenirUnJoueur.php';
require_once __DIR__ . '/src/Controller/ObtenirTousLesJoueurs.php';
require_once __DIR__ . '/src/Controller/SupprimerUnJoueur.php';

require_once __DIR__ . '/src/Controller/CreerUneRencontre.php';
require_once __DIR__ . '/src/Controller/ModifierUneRencontre.php';
require_once __DIR__ . '/src/Controller/ObtenirUneRencontre.php';
require_once __DIR__ . '/src/Controller/ObtenirToutesLesRencontres.php';
require_once __DIR__ . '/src/Controller/SupprimerUneRencontre.php';

require_once __DIR__ . '/src/Controller/CreerUnParticipant.php';
require_once __DIR__ . '/src/Controller/ObtenirUnParticipant.php';
require_once __DIR__ . '/src/Controller/ObtenirTousLesParticipantsDUneRencontre.php';
require_once __DIR__ . '/src/Controller/SupprimerUnParticipant.php';
require_once __DIR__ . '/src/Controller/SupprimerTousLesParticipantsDUneRencontre.php';
require_once __DIR__ . '/src/Controller/ObtenirMoyenneEvaluationJoueur.php';
require_once __DIR__ . '/src/Controller/ModifierEvaluationParticipant.php';
require_once __DIR__ . '/src/Controller/ObtenirStatistiquesGlobales.php';
require_once __DIR__ . '/src/Controller/ObtenirStatistiquesJoueurs.php';
