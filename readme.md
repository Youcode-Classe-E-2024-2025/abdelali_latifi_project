# ABDELALI_LATIFI_PROJECT

## Description
Ce projet est une application web permettant de gérer des **projets** et des **tâches** avec un système de rôles pour les utilisateurs. Elle comprend des fonctionnalités de création, de gestion, et d'affichage des projets et des tâches, tout en intégrant une interface backoffice et frontoffice.

---

## Structure du projet

### Backoffice
Le dossier **backoffice** contient les fichiers nécessaires à la gestion administrative du système.

- **config/connexion.php** : Fichier de connexion à la base de données.
- **controllers/project.php** : Classe permettant de manipuler les données des projets et des tâches (CRUD).
- **database/authentication.php** : Gestion des sessions et de l'authentification.
- **database/create-db-template.php** : Script SQL pour la création des tables de la base de données.
- **logout.php** : Script pour la déconnexion d'un utilisateur.
- **request_team_member.php** : Insertion des demandes pour devenir membre dans la base de données.

### Frontoffice
Le dossier **frontoffice** contient les fichiers destinés à l'interface utilisateur.

- **javascript/dashboard.js** : Script JavaScript gérant les fonctionnalités du tableau de bord utilisateur.
- **dashboard.php** : Page principale du tableau de bord des utilisateurs connectés.
- **home.php** : Page d'accueil du site.
- **index.php** : Point d'entrée principal du site web.
- **registre_page.php** : Page d'inscription pour les nouveaux utilisateurs.
- **guest.php** : Page des invités pour voir les projets publics et envoyer une demande pour devenir membre.

---

## Fonctionnalités principales

### Gestion des projets
- **Lister tous les projets** : Voir les projets existants avec le nom du créateur.
- **Créer un projet** : Ajouter un nouveau projet avec un nom, une description, une visibilité (public/privé), et un créateur.
- **Modifier un projet** : Mettre à jour les informations d’un projet.
- **Supprimer un projet** : Retirer un projet existant.

### Gestion des tâches
- **Lister toutes les tâches** : Afficher les tâches avec leur projet associé et la personne assignée.
- **Créer une tâche** : Ajouter une nouvelle tâche associée à un projet, avec un utilisateur assigné, une date limite, et un statut.
- **Modifier le statut d’une tâche** : Mettre à jour le statut d’une tâche (par exemple, "En cours", "Terminé").
- **Tâches d'un utilisateur** : Voir les tâches assignées à un utilisateur spécifique.

---

## Installation

1. **Cloner le dépôt** :
   ```bash
   git clone https://github.com/Youcode-Classe-E-2024-2025/abdelali_latifi_project.git
