# ⚽ Gestion Équipe de Football

Application web permettant à un entraîneur de gérer son équipe de football (gestion des joueurs, planification des matchs, feuilles de match, évaluations et calcul automatique des statistiques). 

Le projet est conçu selon une architecture **microservices** conteneurisée avec Docker.

**🌐 Démo en ligne :** [https://gestion-equipe-foot.alwaysdata.net](https://gestion-equipe-foot.alwaysdata.net)

>**Identifiants de test :**
> - **Email :** `coach@equipe.fr`
> - **Mot de passe :** `motdepasse`

## 🏛️ Architecture

Le projet est découpé en trois services autonomes communiquant via HTTP/REST et deux bases de données distinctes :

- **`auth/`** : Service d'authentification. Il vérifie les identifiants et génère un jeton JWT signé avec une clé privée (RS256). Il utilise sa propre base de données (`auth_db`).
- **`serveur/`** : API REST de données. Elle gère les ressources métier (joueurs, rencontres, statistiques) et valide les jetons JWT à l'aide de la clé publique. Elle utilise une base de données dédiée (`serveur_db`).
- **`client/`** : Interface web utilisateur. Elle consomme l'API d'authentification et l'API de données, et gère la session utilisateur via le jeton JWT.

| Service | Rôle | Port local | Accès (Production) | Documentation |
| :--- | :--- | :---: | :--- | :--- |
| **`client/`** | Interface web utilisateur | `8080` | [https://gestion-equipe-foot.alwaysdata.net](https://gestion-equipe-foot.alwaysdata.net) | — |
| **`auth/`** | Service d'authentification & signature JWT | `8081` | [https://gestion-equipe-foot-api.alwaysdata.net/auth](https://gestion-equipe-foot-api.alwaysdata.net/auth) | [Documentation API `auth`](auth/README.md) |
| **`serveur/`** | API REST des données métier | `8082` | [https://gestion-equipe-foot-api.alwaysdata.net](https://gestion-equipe-foot-api.alwaysdata.net) | [Documentation API `serveur`](serveur/README.md) |

> **Déploiement continu (CI/CD) :** Chaque microservice dispose d'un workflow GitHub Actions dédié qui déploie automatiquement les modifications vers Alwaysdata lors d'un push sur `main`.

## 🚀 Fonctionnalités

- **Authentification** : Accès protégé par compte entraîneur avec jeton JWT.
- **Gestion des Joueurs** : Suivi de l'effectif (informations personnelles, poste habituel, statut actif/blessé/suspendu/absent).
- **Commentaires** : Ajout et historique de notes de suivi par joueur.
- **Gestion des Matchs** : Planification des rencontres (date, lieu, adversaire) et gestion des résultats.
- **Feuilles de Match** : Préparation de la composition d'équipe (11 titulaires dont 1 gardien, remplaçants).
- **Évaluations** : Notation individuelle des joueurs après chaque match.
- **Statistiques** : Calcul automatique des performances collectives (bilan victoires/nuls/défaites) et individuelles (sélections, temps de jeu, moyennes).

## 💻 Stack Technique

- **Langage** : PHP 8.3 (POO)
- **Bases de données** : MySQL 8.4 (deux instances indépendantes `auth_db` et `serveur_db`)
- **Authentification** : Jetons JWT asymétriques (RS256)
- **Frontend** : HTML5 / CSS3

## ⚙️ Installation et Démarrage

### Prérequis
- Docker & Docker Compose

### 1. Cloner le projet
```bash
git clone https://github.com/leul-mulugeta/gestion-equipe-foot.git
cd gestion-equipe-foot
```

### 2. Configurer l'environnement
Copiez le fichier d'exemple `.env.example` en `.env` :
```bash
cp .env.example .env
```

### 3. Lancer l'application
```bash
docker compose up -d --build
```

Au premier démarrage, le conteneur utilitaire `keygen` génère automatiquement la paire de clés RSA requise pour les jetons JWT, et les bases de données sont initialisées.

### 4. Accès local

- **Application Web** : [http://localhost:8080](http://localhost:8080)
- **API Authentification** : [http://localhost:8081](http://localhost:8081)
- **API Données** : [http://localhost:8082](http://localhost:8082)

## 📁 Structure du Projet

```text
gestion-equipe-foot/
├── auth/                 # Microservice d'authentification (base auth_db)
├── serveur/              # Microservice API REST Données (base serveur_db)
├── client/               # Interface Web utilisateur (consommateur d'APIs)
├── scripts/              # Scripts utilitaires (génération des clés JWT)
├── compose.yaml          # Orchestration des conteneurs Docker
├── .env.example          # Modèle des variables d'environnement
└── README.md
```

## 📄 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.
