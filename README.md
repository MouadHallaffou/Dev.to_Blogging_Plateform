# DEV.TO Blog - Système de Gestion de Contenu

## Contexte du Projet
DEV.TO Blog est une plateforme collaborative visant à permettre aux développeurs de partager des articles, d'explorer du contenu pertinent, et de collaborer efficacement. Le système offre une interface utilisateur fluide pour les visiteurs (front office) et un tableau de bord puissant pour les administrateurs (back office). L'objectif est de créer une communauté active autour des articles techniques, favorisant le partage et la découverte de contenu de qualité.

## Technologies Requises
- **Langage** : PHP 8 avec Programmation Orientée Objet (POO)
- **Base de Données** : MySQL via le driver PDO
- **Frontend** : HTML5, CSS3 (Framework CSS pour responsive design), JavaScript (Validation et interactions dynamiques)
- **Backend** : PHP sécurisé avec validation des données (prévention XSS et CSRF)

## Fonctionnalités Clés

### Back Office (Administrateurs)
- **Gestion des Catégories** :
  - Création, modification et suppression.
  - Association de plusieurs articles à une catégorie.
  - Visualisation des statistiques via des graphiques interactifs.

- **Gestion des Tags** :
  - Création, modification et suppression.
  - Association de tags aux articles pour une recherche précise.
  - Visualisation des statistiques des tags.

- **Gestion des Utilisateurs** :
  - Consultation des profils et gestion des permissions.
  - Attribution de rôles : utilisateur, auteur, administrateur.
  - Suspension ou suppression des utilisateurs.

- **Gestion des Articles** :
  - Consultation, acceptation ou refus des articles soumis.
  - Archivage des articles inappropriés.
  - Visualisation des articles les plus lus.

- **Tableau de Bord** :
  - Statistiques interactives pour les catégories, tags, et utilisateurs.
  - Affichage des 3 meilleurs auteurs.
  - Consultation des articles les plus populaires.

### Front Office (Utilisateurs)
- **Inscription et Connexion** :
  - Création de compte sécurisé.
  - Redirection selon le rôle après connexion.

- **Navigation et Recherche** :
  - Barre de recherche interactive.
  - Navigation dynamique entre catégories et articles.

- **Affichage du Contenu** :
  - Derniers articles et catégories sur la page d'accueil.
  - Page unique pour chaque article avec détails (contenu, catégories, tags, auteur).

- **Espace Auteur** :
  - Création, modification, et suppression d'articles.
  - Association de tags et catégories aux articles.
  - Gestion des articles publiés via un tableau de bord personnel.

## Arborescence du Projet

```
dev-to/
├── src/
│   ├── Article.php         # Gestion des articles
│   ├── User.php            # Gestion des utilisateurs
│   ├── Category.php        # Gestion des catégories
│   ├── Tag.php             # Gestion des tags
│   └── Utils.php           # Fonctions utilitaires
├── sql/
│   └── database.sql        # Script SQL pour la base de données
├── config/
│   └── connection.php      # Configuration de la base de données
├── public/
│   ├── assets/             # Fichiers statiques (CSS, JS, images)
│   └── index.php           # Point d'entrée principal
├── admin/
│   └── dashboard.php       # Tableau de bord des administrateurs
├── composer.json           # Configuration des dépendances           
└── readme.md               # Documentation         
```
```
