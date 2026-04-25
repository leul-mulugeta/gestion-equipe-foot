DROP TABLE IF EXISTS commentaire;
DROP TABLE IF EXISTS participant;
DROP TABLE IF EXISTS joueur;
DROP TABLE IF EXISTS rencontre;

CREATE TABLE joueur (
	joueur_id INT AUTO_INCREMENT PRIMARY KEY,
	numero_licence INT UNIQUE,
	nom VARCHAR(50) NOT NULL,
	prenom VARCHAR(50) NOT NULL,
	date_naissance DATE NOT NULL,
	taille INT NOT NULL,
	poids DECIMAL(4, 1) NOT NULL,
	statut ENUM('ACTIF', 'BLESSE', 'SUSPENDU', 'ABSENT') NOT NULL,
	poste ENUM('GARDIEN', 'DEFENSEUR', 'MILIEU', 'ATTAQUANT') NOT NULL
);

CREATE TABLE commentaire (
	commentaire_id INT AUTO_INCREMENT PRIMARY KEY,
	joueur_id INT NOT NULL,
	contenu VARCHAR(200) NOT NULL
);

CREATE TABLE participant (
	participant_id INT AUTO_INCREMENT PRIMARY KEY,
	joueur_id INT NOT NULL,
	rencontre_id INT NOT NULL,
	type_participation ENUM('TITULAIRE', 'REMPLACANT') NOT NULL,
	poste ENUM('GARDIEN', 'DEFENSEUR', 'MILIEU', 'ATTAQUANT') NOT NULL,
	evaluation INT
);

CREATE TABLE rencontre (
	rencontre_id INT AUTO_INCREMENT PRIMARY KEY,
	date_heure DATETIME NOT NULL,
	lieu ENUM('DOMICILE', 'EXTERIEUR') NOT NULL,
	adresse VARCHAR(100) NOT NULL,
	nom_equipe_adverse VARCHAR(50) NOT NULL,
	resultat ENUM('VICTOIRE', 'DEFAITE', 'NUL'),
	score_equipe_locale INT,
	score_equipe_adverse INT
);