# ⚽ Gestion Équipe de Football

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

2. **Base de données** :
   Vous devez disposer d'un serveur MySQL (via **XAMPP**, en ligne ou un autre outil).
   - Créez une nouvelle base de données via `phpMyAdmin` ou en ligne de commande.
   - Exécutez les scripts SQL situés dans le dossier `sql/` dans l'ordre suivant :
     1. `sql/01_create_tables.sql` (**Obligatoire** : crée la structure).
     2. `sql/02_add_constraints.sql` (**Obligatoire** : ajoute les relations).
     3. `sql/test.sql` (**Optionnel** : ajoute des données de démonstration).

3. **Configuration** :
   - Renommez le fichier `config.sample.php` vers `config.php`.
   - Modifiez le pour configurer vos accès à la base de données.
   - L'identifiant et le mot de passe du coach sont également modifiables dans ce fichier (par défaut : `coach@equipe.fr` / `motdepasse`).

4. **Exécution** :
   Lancez votre serveur web. Reportez-vous à la section **Configuration du Serveur Web** ci-dessous pour plus de détails sur le routage.

## ⚙️ Configuration du Serveur Web

> **⚠️ Important :** Pour que le système de routage fonctionne, l'application **doit être servie à la racine de votre domaine ou port** (ex: `http://localhost:8000` ou `http://gestion-equipe-foot.local`). Elle ne fonctionnera pas si elle est lancée dans un sous-dossier (ex: `http://localhost/gestion-equipe-foot/public`).

### Option 1 : Apache (VirtualHost recommandé)
Un fichier `public/.htaccess` est déjà inclus. Assurez-vous que le module `mod_rewrite` est activé dans votre configuration Apache.
Configurez votre **VirtualHost** en utilisant le **chemin absolu** vers votre projet :
```apache
<VirtualHost *:80>
    ServerName gestion-equipe-foot.local
    # Remplacez CHEMIN_ABSOLU par le chemin réel sur votre machine
    DocumentRoot "CHEMIN_ABSOLU/gestion-equipe-foot/public"
    <Directory "CHEMIN_ABSOLU/gestion-equipe-foot/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Option 2 : Serveur intégré PHP (Méthode la plus simple)
Exécutez la commande suivante à la racine du projet :
```bash
php -S localhost:8000 -t public
```
L'application sera alors accessible sur `http://localhost:8000`.

## 📁 Structure du projet
- `public/` : Fichiers accessibles (index.php, CSS).
- `src/Controller/` : Logique de contrôle.
- `src/Model/` : Objets métiers (Entity) et accès aux données (DAO).
- `templates/` : Vues de l'application (HTML/PHP).
- `sql/` : Scripts de création de la base de données.

## 🧑‍💻 À propos
Projet réalisé dans le cadre du **BUT Informatique** par Leul Mulugeta et Mathias Chatelard.

## 📄 Licence
Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.
