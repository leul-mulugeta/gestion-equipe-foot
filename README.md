# ⚽ Gestion Équipe de Football

## 🚧 Migration en cours vers une architecture microservices

Ce projet migre progressivement d'un monolithe PHP vers 3 microservices (`auth/`, `serveur/`, `client/`).

En local : `docker compose -f compose.microservices.yaml up -d --build` pour tester la nouvelle stack (le monolithe continue de tourner via `compose.yaml`).

## 🛠️ Présentation
Cette application web permet à un coach de gérer son équipe de football. Elle est construite avec une architecture **MVC** (Modèle-Vue-Contrôleur) et utilise le pattern **DAO** (Data Access Object) pour la gestion des données. Elle permet de gérer les joueurs, les rencontres, les statistiques de performance et les évaluations.

**🌐 [Démo en ligne](https://gestion-equipe-foot.alwaysdata.net/)**

>**Identifiants de test :**
> - **Email :** `coach@equipe.fr`
> - **Mot de passe :** `motdepasse`

## 🚀 Fonctionnalités
- **Compte Coach unique** : Accès protégé pour la gestion globale de l'équipe.
- **Gestion des Joueurs** : Suivi des informations, des postes et des statuts (actif, blessé, etc.).
- **Gestion des Matchs** : Planification des rencontres et saisie des résultats.
- **Feuilles de Match** : Sélection des participants pour chaque rencontre.
- **Évaluations** : Système de notes et de commentaires par joueur après les matchs.
- **Statistiques** : Calcul automatique des performances individuelles et collectives.

## 💻 Stack Technique
- **Langage** : PHP 8.1+ (POO)
- **Base de données** : MySQL
- **Architecture** : MVC & DAO
- **Frontend** : HTML5 / CSS3

## ⚙️ Installation et Configuration
1. **Cloner le projet** :
   ```bash
   git clone https://github.com/leul-mulugeta/gestion-equipe-foot.git
   cd gestion-equipe-foot
   ```

2. **Préparer l'environnement** :
   - Renommez le fichier `.env.example` en `.env`.
   - Modifiez les valeurs à l'intérieur selon vos préférences.

### Option A : La voie rapide avec Docker (Recommandée)
Si vous ne souhaitez **pas** insérer les données de démonstration, supprimez simplement le fichier `sql/data_test.sql` de votre dossier avant de lancer Docker.

Vous pouvez lancer l'application en une seule commande :

```bash
docker compose up -d --build
```

L'application est prête et accessible sur `http://localhost`.

### Option B : La voie classique (Laragon, XAMPP, WAMP)
Si vous préférez utiliser votre propre serveur local.

1. **Base de données** :
   - Créez une base de données nommée `gestion-equipe-foot`.
   - Exécutez les scripts SQL situés dans le dossier `sql/` dans l'ordre suivant :
     1. `sql/01_create_tables.sql` (**Obligatoire** : crée la structure).
     2. `sql/02_add_constraints.sql` (**Obligatoire** : ajoute les relations).
     3. `sql/data_test.sql` (**Optionnel** : ajoute des données de démonstration).

2. **Lancer le serveur Web** :
   La méthode la plus simple est d'utiliser le serveur intégré de PHP. Dans votre terminal, à la racine du projet, tapez :
   ```bash
   php -S localhost:8000 -t public
   ```
   L'application sera alors accessible sur `http://localhost:8000`.

   *(Alternative : Si vous configurez un VirtualHost Apache sur Laragon/XAMPP, assurez-vous de faire pointer le "Document Root" directement vers le sous-dossier `public/`).*

## 📁 Structure du projet
- `public/` : Fichiers accessibles (index.php, CSS).
- `src/Controller/` : Logique de contrôle.
- `src/Model/` : Objets métiers (Entity) et accès aux données (DAO).
- `src/Vue/` : Vues de l'application (HTML/PHP).
- `sql/` : Scripts de création de la base de données.

## 🧑‍💻 À propos
Projet réalisé dans le cadre du **BUT Informatique** par Leul Mulugeta et Mathias Chatelard.

## 📄 Licence
Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.
