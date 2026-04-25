-- Désactivation des vérifications de clés étrangères pour permettre le nettoyage dans n'importe quel ordre
SET FOREIGN_KEY_CHECKS = 0;

-- Nettoyage des tables
DELETE FROM commentaire;
DELETE FROM participant;
DELETE FROM rencontre;
DELETE FROM joueur;

-- Réinitialisation des compteurs Auto-Increment à 1
ALTER TABLE joueur AUTO_INCREMENT = 1;
ALTER TABLE rencontre AUTO_INCREMENT = 1;
ALTER TABLE participant AUTO_INCREMENT = 1;
ALTER TABLE commentaire AUTO_INCREMENT = 1;

-- Réactivation des vérifications
SET FOREIGN_KEY_CHECKS = 1;

-- Insertion des 20 joueurs
INSERT INTO joueur (numero_licence, nom, prenom, date_naissance, taille, poids, statut, poste) VALUES
(1001, 'Lloris', 'Hugo', '1986-12-26', 188, 82.0, 'ACTIF', 'GARDIEN'),
(1002, 'Maignan', 'Mike', '1995-07-03', 191, 89.0, 'ACTIF', 'GARDIEN'),
(1003, 'Pavard', 'Benjamin', '1996-03-28', 186, 81.0, 'ACTIF', 'DEFENSEUR'),
(1004, 'Varane', 'Raphael', '1993-04-25', 191, 79.0, 'ACTIF', 'DEFENSEUR'),
(1005, 'Koundé', 'Jules', '1998-11-12', 180, 75.0, 'ACTIF', 'DEFENSEUR'),
(1006, 'Hernandez', 'Theo', '1997-10-06', 184, 81.0, 'BLESSE', 'DEFENSEUR'),
(1007, 'Konaté', 'Ibrahima', '1999-05-25', 194, 95.0, 'ACTIF', 'DEFENSEUR'),
(1008, 'Tchouaméni', 'Aurélien', '2000-01-27', 187, 81.0, 'ACTIF', 'MILIEU'),
(1009, 'Rabiot', 'Adrien', '1995-04-03', 188, 71.0, 'ACTIF', 'MILIEU'),
(1010, 'Camavinga', 'Eduardo', '2002-11-10', 182, 68.0, 'ACTIF', 'MILIEU'),
(1011, 'Griezmann', 'Antoine', '1991-03-21', 176, 73.0, 'ACTIF', 'MILIEU'),
(1012, 'Mbappé', 'Kylian', '1998-12-20', 178, 75.0, 'ACTIF', 'ATTAQUANT'),
(1013, 'Giroud', 'Olivier', '1986-09-30', 193, 91.0, 'ACTIF', 'ATTAQUANT'),
(1014, 'Dembélé', 'Ousmane', '1997-05-15', 178, 67.0, 'SUSPENDU', 'ATTAQUANT'),
(1015, 'Coman', 'Kingsley', '1996-06-13', 179, 75.0, 'ACTIF', 'ATTAQUANT'),
(1016, 'Upamecano', 'Dayot', '1998-10-27', 186, 90.0, 'ACTIF', 'DEFENSEUR'),
(1017, 'Fofana', 'Youssouf', '1999-01-10', 185, 74.0, 'ACTIF', 'MILIEU'),
(1018, 'Thuram', 'Marcus', '1997-08-06', 192, 90.0, 'ACTIF', 'ATTAQUANT'),
(1019, 'Saliba', 'William', '2001-03-24', 192, 85.0, 'ACTIF', 'DEFENSEUR'),
(1020, 'Areola', 'Alphonse', '1993-02-27', 195, 94.0, 'ACTIF', 'GARDIEN');

-- Insertion des Commentaires
INSERT INTO commentaire (joueur_id, contenu) VALUES
(1, 'Capitaine exemplaire, très rassurant sur sa ligne.'),
(4, 'Un peu fébrile sur les relances lors du dernier entraînement.'),
(6, 'Blessure à la cuisse, indisponible pour 3 semaines.'),
(12, 'Excellente forme physique, très rapide.'),
(14, 'Suspendu suite au carton rouge du dernier match.');

-- Insertion des 7 Rencontres
INSERT INTO rencontre (date_heure, lieu, adresse, nom_equipe_adverse, resultat, score_equipe_locale, score_equipe_adverse) VALUES
('2025-11-10 20:45:00', 'DOMICILE', 'Stade de France, Paris', 'Allemagne', 'VICTOIRE', 3, 1),
('2025-11-25 20:45:00', 'EXTERIEUR', 'Wembley, Londres', 'Angleterre', 'NUL', 2, 2),
('2025-12-05 20:45:00', 'DOMICILE', 'Stade de France, Paris', 'Italie', 'DEFAITE', 0, 1),
('2025-12-15 20:45:00', 'EXTERIEUR', 'Santiago Bernabéu, Madrid', 'Espagne', 'VICTOIRE', 2, 0),
('2026-01-10 21:00:00', 'DOMICILE', 'Stade Vélodrome, Marseille', 'Brésil', 'VICTOIRE', 4, 1),
('2026-02-05 20:45:00', 'DOMICILE', 'Stade de France, Paris', 'Portugal', NULL, NULL, NULL),
('2026-02-12 20:45:00', 'EXTERIEUR', 'Estadio da Luz, Lisbonne', 'Portugal', NULL, NULL, NULL);

-- Insertion des Participants
-- Match 1
INSERT INTO participant (joueur_id, rencontre_id, type_participation, poste, evaluation) VALUES
(1, 1, 'TITULAIRE', 'GARDIEN', 4),
(3, 1, 'TITULAIRE', 'DEFENSEUR', 3), (4, 1, 'TITULAIRE', 'DEFENSEUR', 4), (5, 1, 'TITULAIRE', 'DEFENSEUR', 4), (7, 1, 'TITULAIRE', 'DEFENSEUR', 3),
(8, 1, 'TITULAIRE', 'MILIEU', 5), (9, 1, 'TITULAIRE', 'MILIEU', 4), (11, 1, 'TITULAIRE', 'MILIEU', 5),
(12, 1, 'TITULAIRE', 'ATTAQUANT', 5), (13, 1, 'TITULAIRE', 'ATTAQUANT', 3), (15, 1, 'TITULAIRE', 'ATTAQUANT', 4),
(18, 1, 'REMPLACANT', 'ATTAQUANT', 4), (17, 1, 'REMPLACANT', 'MILIEU', 3);

-- Match 2
INSERT INTO participant (joueur_id, rencontre_id, type_participation, poste, evaluation) VALUES
(1, 2, 'TITULAIRE', 'GARDIEN', 3),
(3, 2, 'TITULAIRE', 'DEFENSEUR', 2), (19, 2, 'TITULAIRE', 'DEFENSEUR', 3), (7, 2, 'TITULAIRE', 'DEFENSEUR', 3), (16, 2, 'TITULAIRE', 'DEFENSEUR', 3),
(8, 2, 'TITULAIRE', 'MILIEU', 4), (10, 2, 'TITULAIRE', 'MILIEU', 3), (17, 2, 'TITULAIRE', 'MILIEU', 3),
(12, 2, 'TITULAIRE', 'ATTAQUANT', 4), (18, 2, 'TITULAIRE', 'ATTAQUANT', 3), (15, 2, 'TITULAIRE', 'ATTAQUANT', 3),
(13, 2, 'REMPLACANT', 'ATTAQUANT', 4), (11, 2, 'REMPLACANT', 'MILIEU', 5);

-- Match 3
INSERT INTO participant (joueur_id, rencontre_id, type_participation, poste, evaluation) VALUES
(2, 3, 'TITULAIRE', 'GARDIEN', 4),
(4, 3, 'TITULAIRE', 'DEFENSEUR', 2), (5, 3, 'TITULAIRE', 'DEFENSEUR', 2), (16, 3, 'TITULAIRE', 'DEFENSEUR', 3), (19, 3, 'TITULAIRE', 'DEFENSEUR', 2),
(9, 3, 'TITULAIRE', 'MILIEU', 2), (11, 3, 'TITULAIRE', 'MILIEU', 3), (17, 3, 'TITULAIRE', 'MILIEU', 2),
(12, 3, 'TITULAIRE', 'ATTAQUANT', 2), (14, 3, 'TITULAIRE', 'ATTAQUANT', 1), (13, 3, 'TITULAIRE', 'ATTAQUANT', 2),
(18, 3, 'REMPLACANT', 'ATTAQUANT', 3);

-- Match 4
INSERT INTO participant (joueur_id, rencontre_id, type_participation, poste, evaluation) VALUES
(1, 4, 'TITULAIRE', 'GARDIEN', 5),
(3, 4, 'TITULAIRE', 'DEFENSEUR', 4), (4, 4, 'TITULAIRE', 'DEFENSEUR', 4), (5, 4, 'TITULAIRE', 'DEFENSEUR', 5), (7, 4, 'TITULAIRE', 'DEFENSEUR', 4),
(8, 4, 'TITULAIRE', 'MILIEU', 4), (11, 4, 'TITULAIRE', 'MILIEU', 5), (10, 4, 'TITULAIRE', 'MILIEU', 4),
(12, 4, 'TITULAIRE', 'ATTAQUANT', 4), (15, 4, 'TITULAIRE', 'ATTAQUANT', 4), (13, 4, 'TITULAIRE', 'ATTAQUANT', 3),
(9, 4, 'REMPLACANT', 'MILIEU', 4), (16, 4, 'REMPLACANT', 'DEFENSEUR', 4);

-- Match 5
INSERT INTO participant (joueur_id, rencontre_id, type_participation, poste, evaluation) VALUES
(1, 5, 'TITULAIRE', 'GARDIEN', 4),
(5, 5, 'TITULAIRE', 'DEFENSEUR', 4), (7, 5, 'TITULAIRE', 'DEFENSEUR', 4), (16, 5, 'TITULAIRE', 'DEFENSEUR', 4), (19, 5, 'TITULAIRE', 'DEFENSEUR', 4),
(10, 5, 'TITULAIRE', 'MILIEU', 5), (11, 5, 'TITULAIRE', 'MILIEU', 5), (17, 5, 'TITULAIRE', 'MILIEU', 4),
(12, 5, 'TITULAIRE', 'ATTAQUANT', 5), (15, 5, 'TITULAIRE', 'ATTAQUANT', 5), (18, 5, 'TITULAIRE', 'ATTAQUANT', 4),
(13, 5, 'REMPLACANT', 'ATTAQUANT', 4), (3, 5, 'REMPLACANT', 'DEFENSEUR', 3);
