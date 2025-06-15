# IIM Symfony API Platform

Ce projet est une application Symfony 6.4 intégrant API Platform v4, Doctrine, un système de sécurité par rôles, des fixtures de test, et une API RESTful pour la gestion de produits, utilisateurs et notifications.

## Prérequis

- PHP >= 8.1
- Composer
- Une base de données compatible Doctrine (MySQL, PostgreSQL, SQLite…)
- [Symfony CLI (optionnel mais recommandé)](https://symfony.com/download)

## Installation

1. **Cloner le dépôt**

   ```bash
   git clone <url-du-repo>
   cd IIM_Symfony_NEW
   ```

2. **Installer les dépendances**

   ```bash
   composer install
   ```

3. **Configurer l'environnement**

   Copie le fichier `.env` si besoin et configure la connexion à ta base de données :

   ```bash
   cp .env .env.local
   # puis édite .env.local pour adapter la variable DATABASE_URL
   ```

   Exemple pour MySQL :
   ```
   DATABASE_URL="mysql://user:password@127.0.0.1:3306/nom_de_la_base?serverVersion=8.0"
   ```

4. **Créer la base de données et exécuter les migrations**

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Charger les données de test (fixtures)**

   ```bash
   php bin/console doctrine:fixtures:load
   ```

6. **Lancer le serveur de développement**

   ```bash
   symfony server:start
   # ou
   php -S localhost:8000 -t public
   ```

7. **Accéder à l'API et à la documentation**

   - Documentation Swagger : [http://localhost:8000/api](http://localhost:8000/api)
   - Documentation ReDoc : [http://localhost:8000/api/docs](http://localhost:8000/api/docs)

---

## Fonctionnalités principales

- **Gestion des utilisateurs** (admin et utilisateurs simples)
- **Gestion des produits** (CRUD, panier, achat par points)
- **Notifications** (liées aux actions des utilisateurs)
- **API sécurisée** (JWT ou session, selon config)
- **Endpoints personnalisés via API Platform v4 et providers custom**
- **Fixtures pour peupler la base de données de test**

---

## Modèle de données

- **User** : email, mot de passe, nom, prénom, points, actif, rôles, produits
- **Product** : nom, prix, catégorie, description, owner (User), timestamps
- **Notification** : label, user (User), timestamps

---

## Accès administrateur de test

Après chargement des fixtures, tu peux te connecter avec :

- **Email** : `admin@example.com`
- **Mot de passe** : `admin123`

---

## Commandes utiles


- Vider le cache :  
  ```bash
  php bin/console cache:clear
  ```
- Générer une nouvelle migration :  
  ```bash
  php bin/console make:migration
  ```

---

## Débogage

- Les logs applicatifs sont dans `var/log/`.
- Pour vérifier si un provider custom est appelé, tu peux temporairement ajouter un log ou un `throw new \Exception()` dans la méthode concernée.

---

