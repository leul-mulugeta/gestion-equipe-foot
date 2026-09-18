# Service de Données (`serveur`)

Microservice dédié à la gestion des données de l'équipe (joueurs, rencontres, feuilles de match, évaluations et statistiques).

## 1. Rôle et Choix techniques

* **Rôle** : fournir l'API REST permettant la consultation et la modification de toutes les données du club.
* **PHP 8 natif** : aucun framework ni dépendance externe.
* **Validation des jetons JWT (RS256)** :
  * Le service `serveur` vérifie la signature des jetons avec la **clé publique** de manière autonome.
* **Base de données dédiée** : stockage des données dans une base MySQL indépendante (`serveur_db`).
* **Format de réponse unifié** : structure JSON standardisée `{ status, status_code, status_message, data }`.

## 2. Structure des fichiers

```text
serveur/
├── config.php            # Configuration et variables d'environnement
├── init.php              # Chargement des classes
├── sql/                  # Scripts SQL de création et d'initialisation de la base de données
├── public/
│   └── index.php         # Point d'entrée de l'API, validation JWT et routage
└── src/
    ├── Api.php           # Réponses JSON standardisées
    ├── BearerToken.php   # Extraction du jeton dans l'en-tête Authorization
    ├── JWTVerifier.php   # Validation RS256 du jeton via la clé publique
    ├── Mapper.php        # Transformation Entity <-> JSON
    ├── Controller/       # Cas d'usage métier
    ├── Model/            # DAO, Entities et Enums
    └── Router/           # Routage par ressource (joueurs, rencontres, etc.)
```

## 3. Configuration

Les variables d'environnement sont définies dans le fichier `.env` à la racine du projet et injectées via Docker Compose (`compose.microservices.yaml`) :

* `DB_HOST` : hôte de la base (ex. `serveur-db` sous Docker)
* `DB_NAME` : nom de la base (ex. `serveur_db`)
* `DB_USER` & `DB_PASSWORD` : identifiants MySQL
* `JWT_PUBLIC_KEY_PATH` : chemin absolu dans le conteneur vers la clé publique `.pem`

## 4. Fonctionnement global de l'API

### En-têtes HTTP

Toutes les requêtes nécessitent obligatoirement le jeton d'authentification :
* `Authorization: Bearer <token_jwt>`

Pour les requêtes transmettant des données (`POST`, `PUT`, `PATCH`) :
* `Content-Type: application/json`

### Structure des réponses

#### En cas de succès
```json
{
  "status": "success",
  "status_code": 200,
  "status_message": "Message de confirmation",
  "data": { ... }
}
```

#### En cas d'erreur
```json
{
  "status": "error",
  "status_code": 400,
  "status_message": "Message explicatif",
  "data": null
}
```

### Codes et erreurs retournés par l'API

* **`400 Bad Request`**
  * `"Le corps de la requête est invalide."` *(JSON mal formé ou non-tableau)*
  * `"Identifiant manquant."` *(identifiant absent dans l'URL pour PUT, PATCH ou DELETE)*
  * Erreurs de validation de champs *(ex. `"Tous les champs sont obligatoires : ..."`)*
* **`401 Unauthorized`**
  * `"Non authentifié."` *(en-tête Authorization manquant ou vide)*
  * `"Token invalide ou expiré."` *(signature invalide ou expiration dépassée)*
* **`404 Not Found`**
  * `"Ressource inconnue."` *(URL inexistante)*
  * `"Joueur introuvable."` / `"Rencontre introuvable."` *(identifiant inexistant en base)*
* **`405 Method Not Allowed`**
  * `"Méthode non autorisée."` *(verbe HTTP non supporté sur la ressource demandée)*
* **`409 Conflict`**
  * Conflits métier *(ex. `"Impossible d'enregistrer le résultat d'une rencontre qui n'a pas encore été jouée."`)*
* **`500 Internal Server Error`**
  * `"Une erreur est survenue lors de l'accès aux données."` *(échec de la base de données)*
  * `"Une erreur serveur est survenue lors du traitement."` *(erreur inattendue)*

## 5. Référence de l'API

### Joueurs (`/joueurs`)

| Méthode | Route | Description | Corps requis (JSON) | `data` retournée |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/joueurs` | Liste tous les joueurs | Aucun | Liste d'objets joueur |
| `POST` | `/joueurs` | Ajoute un nouveau joueur | Objet joueur | `null` (201) |
| `GET` | `/joueurs/moyennes-evaluations` | Moyenne des évaluations de tous les joueurs | Aucun | Liste des moyennes |
| `GET` | `/joueurs/{id}` | Détail d'un joueur | Aucun | Objet joueur |
| `PUT` | `/joueurs/{id}` | Modifie les informations d'un joueur | Objet joueur | `null` (200) |
| `DELETE` | `/joueurs/{id}` | Supprime un joueur | Aucun | `null` (200) |
| `GET` | `/joueurs/{id}/commentaires` | Liste des commentaires d'un joueur | Aucun | Liste d'objets commentaire |
| `POST` | `/joueurs/{id}/commentaires` | Ajoute un commentaire sur un joueur | `{"contenu": "Texte..."}` | `null` (201) |

#### Format d'un objet joueur (création / modification)
```json
{
  "numeroDeLicence": 12345678,
  "nom": "Dupont",
  "prenom": "Jean",
  "dateDeNaissance": "2000-05-15",
  "taille": 182,
  "poids": 75.5,
  "statut": "ACTIF",
  "poste": "ATTAQUANT"
}
```
* **`statut`** : `ACTIF`, `BLESSE`, `SUSPENDU`, `ABSENT`
* **`poste`** : `GARDIEN`, `DEFENSEUR`, `MILIEU`, `ATTAQUANT`

---

### Commentaires (`/commentaires`)

| Méthode | Route | Description | `data` retournée |
| :--- | :--- | :--- | :--- |
| `DELETE` | `/commentaires/{id}` | Supprime un commentaire par son identifiant | `null` (200) |

---

### Rencontres (`/rencontres`)

| Méthode | Route | Description | Corps requis (JSON) | `data` retournée |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/rencontres` | Liste toutes les rencontres | Aucun | Liste d'objets rencontre |
| `POST` | `/rencontres` | Planifie une nouvelle rencontre | Objet planification | `null` (201) |
| `GET` | `/rencontres/{id}` | Détail d'une rencontre | Aucun | Objet rencontre |
| `PUT` | `/rencontres/{id}` | Modifie la planification d'une rencontre | Objet planification | `null` (200) |
| `DELETE` | `/rencontres/{id}` | Supprime une rencontre | Aucun | `null` (200) |
| `GET` | `/rencontres/{id}/participants` | Feuille de match (participants) | Aucun | Liste d'objets participant |
| `PUT` | `/rencontres/{id}/participants` | Enregistre la feuille de match | Liste de participants | `null` (200) |
| `PATCH` | `/rencontres/{id}/resultat` | Enregistre le score du match | Scores du match | `null` (200) |
| `PATCH` | `/rencontres/{id}/evaluations` | Enregistre les notes des participants | Liste d'évaluations | `null` (200) |

#### Format de la planification d'une rencontre (création / modification)
```json
{
  "dateEtHeure": "2026-10-20 15:00:00",
  "lieu": "DOMICILE",
  "adresse": "Stade Municipal, 12 rue du Sport",
  "nomEquipeAdverse": "FC Étoile"
}
```
* **`lieu`** : `DOMICILE`, `EXTERIEUR`

#### Format de la feuille de match (`PUT /rencontres/{id}/participants`)
```json
[
  { "joueurId": 1, "typeDeParticipation": "TITULAIRE", "poste": "GARDIEN" },
  { "joueurId": 2, "typeDeParticipation": "REMPLACANT", "poste": "DEFENSEUR" }
]
```
* **`typeDeParticipation`** : `TITULAIRE`, `REMPLACANT`

#### Format du résultat (`PATCH /rencontres/{id}/resultat`)
```json
{
  "scoreEquipeLocale": 2,
  "scoreEquipeAdverse": 1
}
```

#### Format des évaluations (`PATCH /rencontres/{id}/evaluations`)
```json
[
  { "participantId": 10, "evaluation": 4 }
]
```
* **`evaluation`** : entier compris entre 1 et 5.

---

### Statistiques (`/statistiques`)

| Méthode | Route | Description | `data` retournée |
| :--- | :--- | :--- | :--- |
| `GET` | `/statistiques/globales` | Statistiques globales de l'équipe (matchs, victoires, nuls, défaites, buts, pourcentages) | Objet de statistiques globales |
| `GET` | `/statistiques/joueurs` | Statistiques individuelles (sélections, titularisations, temps de jeu, moyennes de notes) | Liste d'objets statistiques par joueur |
