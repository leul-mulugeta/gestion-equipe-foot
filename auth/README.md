# Service d'Authentification (`auth`)

Microservice dédié à la gestion des utilisateurs et à l'émission des jetons d'accès JWT pour l'application.

## 1. Rôle et Choix techniques

* **Rôle** : vérifier les identifiants de connexion et délivrer un jeton JWT valide pour autoriser les requêtes sur l'API de données (`serveur`).
* **PHP 8 natif** : aucun framework ni dépendance externe.
* **Mots de passe sécurisés** : hachage avec l'API standard de PHP (`password_hash` / `password_verify` en BCRYPT).
* **Signature asymétrique RS256** :
  * Le service `auth` détient la **clé privée** pour signer les jetons.
  * Le service `serveur` vérifie les jetons avec la **clé publique** correspondante.
* **Base de données dédiée** : stockage des utilisateurs dans une base MySQL indépendante (`auth_db`).
* **Format de réponse unifié** : structure JSON standardisée `{ status, status_code, status_message, data }`.

## 2. Structure des fichiers

```text
auth/
├── config.php            # Configuration et variables d'environnement
├── init.php              # Chargement des classes
├── schema.sql            # Script SQL de création de la base de données.
├── public/
│   └── index.php         # Point d'entrée de l'API et routage
└── src/
    ├── Api.php           # Réponses JSON standardisées
    ├── Auth.php          # Logique de connexion et vérification du mot de passe
    ├── DBConnection.php  # Connexion PDO à la base de données
    └── JWTSigner.php     # Signature RS256 du token JWT
```

## 3. Configuration

Les variables d'environnement sont définies dans le fichier `.env` à la racine du projet et injectées via Docker Compose (`compose.microservices.yaml`) :

* `DB_HOST` : hôte de la base (ex. `auth-db` sous Docker)
* `DB_NAME` : nom de la base (ex. `auth_db`)
* `DB_USER` & `DB_PASSWORD` : identifiants MySQL
* `JWT_PRIVATE_KEY_PATH` : chemin absolu dans le conteneur vers la clé privée `.pem`

## 4. Référence de l'API

### `POST /auth/login`

Authentifie un utilisateur et retourne un jeton JWT valable 1 heure.

* **Méthode** : `POST`
* **URL** : `/auth/login`
* **Header requis** : `Content-Type: application/json`

#### Corps de la requête (JSON)

```json
{
  "email": "coach@equipe.fr",
  "password": "motdepasse"
}
```

> **Identifiants de test :** le compte `coach@equipe.fr` / `motdepasse` est créé par défaut via `schema.sql`.

#### Réponse avec succès (`200 OK`)

```json
{
  "status": "success",
  "status_code": 200,
  "status_message": "Authentification réussie.",
  "data": {
    "token": "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9..."
  }
}
```

> **Contenu du JWT généré** :
> * Header : `{"alg": "RS256", "typ": "JWT"}`
> * Payload : `{"email": "coach@equipe.fr", "exp": <timestamp>}` (durée de validité : 1 heure)

#### Gestion des erreurs

En cas d'erreur, l'API renvoie toujours la même structure :

```json
{
  "status": "error",
  "status_code": 400,
  "status_message": "Message explicatif",
  "data": null
}
```

**Codes et messages retournés par l'API :**

* **`400 Bad Request`**
  * `"Le corps de la requête est invalide."` *(JSON mal formé ou non-tableau)*
  * `"Email et mot de passe obligatoires."` *(champ absent ou vide)*
* **`401 Unauthorized`**
  * `"Email ou mot de passe incorrect."` *(identifiants invalides)*
* **`404 Not Found`**
  * `"Ressource inconnue."` *(URL différente de `/auth/login`)*
* **`405 Method Not Allowed`**
  * `"Méthode non autorisée."` *(requête autre que `POST`)*
* **`500 Internal Server Error`**
  * `"Une erreur est survenue lors de l'accès aux données."` *(échec de la base de données)*
  * `"Une erreur serveur est survenue lors du traitement."` *(clé privée introuvable/invalide ou erreur inattendue)*
