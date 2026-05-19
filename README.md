# R-Tech — E-commerce de Produits High-Tech

![Symfony](https://img.shields.io/badge/Symfony-6.x%20%7C%207.x-000000?style=for-the-badge&logo=symfony&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)

**R-Tech** est une application web e-commerce développée avec le framework **Symfony**, spécialisée dans la vente de produits high-tech. 

> **Note sur l'extensibilité :** Bien que l'interface HTML et l'habillage graphique soient configurés pour la marque *R-Tech* (produits high-tech), le modèle de données et l'architecture du système peuvent être facilement adaptés pour d'autres domaines d'activité.

---

## Sommaire
- [Fonctionnalités](#fonctionnalités)
- [Installation](#installation)
  - [Prérequis (PHP, Composer, Symfony)](#prérequis-php-composer-symfony)
  - [Cloner le projet](#cloner-le-projet)
  - [Configuration (.env & Base de données)](#configuration-env--base-de-données)
- [Structure du projet](#structure-du-projet)
- [Sécurité](#sécurité)
- [Auteur](#auteur)

---

## Fonctionnalités

### Pour les utilisateurs
- **Authentification :** Inscription et connexion sécurisées gérées nativement par Symfony.
- **Catalogue :**
  - Recherche de produits par nom.
  - Filtres par **catégorie** et par **prix** (le filtrage par état a été retiré).
- **Panier :** Ajout, modification et suppression d'articles avec calcul en temps réel.
- **Paiement Sécurisé :** Intégration complète avec l'API **Stripe** pour la gestion des transactions bancaires.

### Pour les administrateurs
- **Back-Office complet :** Intégration du puissant bundle **EasyAdmin** permettant une gestion simplifiée et complète (CRUD) des produits, des catégories, des utilisateurs et des commandes.

---

## Installation

### Prérequis (PHP, Composer, Symfony)

Ce projet nécessite un environnement PHP moderne. WampServer n'est plus requis car la base de données est hébergée à distance.

#### 1. Installer PHP
- **Windows :** Téléchargez PHP (8.2+) sur [windows.php.net](https://windows.php.net/). Extrayez-le et ajoutez le chemin vers le dossier PHP dans votre variable d'environnement `PATH`. Assurez-vous que PHP est correctement configuré.
- **macOS / Linux :** Utilisez votre gestionnaire de paquets (ex: `brew install php` ou `sudo apt install php`).

#### 2. Installer Composer
Composer est le gestionnaire de dépendances PHP essentiel pour Symfony.
- Suivez les instructions officielles sur [getcomposer.org](https://getcomposer.org/download/).
- Vérifiez l'installation en tapant : `composer --version`

#### 3. Installer la CLI Symfony
La CLI Symfony facilite le développement local.
- Suivez le guide sur [symfony.com/download](https://symfony.com/download).
- Vérifiez l'installation en tapant : `symfony -v`

---

### Cloner le projet

```bash
git clone https://github.com/raydafi/e-commerce_R-Tech.git
cd e-commerce_R-Tech
```

Installez ensuite toutes les dépendances PHP du projet :

```bash
composer install
```

---

## Configuration (.env & Base de données distant)

La base de données du projet est hébergée à distance sur Alwaysdata.

### Étape 1 : Configurer le fichier .env
À la racine du projet, dupliquez le fichier `.env` en `.env.local` s'il n'existe pas, puis configurez votre chaîne de connexion à la base de données Alwaysdata ainsi que vos clés Stripe :

```ini
# Configuration de la base de données Alwaysdata
DATABASE_URL="mysql://USER_ALWAYSDATA:PASSWORD_ALWAYSDATA@ssh-USER_ALWAYSDATA.alwaysdata.net:3306/DB_NAME?serverVersion=8.0.0&charset=utf8mb4"

# Configuration Stripe
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
```

### Étape 2 : Export des entités et migration vers la BDD

Grâce à Doctrine (l'ORM de Symfony), vous n'avez pas besoin de charger un fichier SQL manuellement. Exécutez simplement les lignes de commande suivantes pour créer et exporter le schéma des entités :

```bash
# Générer le fichier de migration basé sur les entités Symfony
php bin/console make:migration

# Exécuter la migration pour créer les tables sur la BDD distante
php bin/console doctrine:migrations:migrate
```

Le schéma généré sur votre base de données comprendra les tables suivantes : `category`, `order`, `order_item`, `product`, `user`.

### Étape 3 : Lancer le projet en local

Pour démarrer le serveur de développement interne de Symfony, exécutez :

```bash
symfony server:start
```

Votre site est maintenant accessible à l'adresse : http://127.0.0.1:8000

---

## Structure du projet (Symfony)

Le projet suit la structure standard et moderne d'une application Symfony (architecture MVC) :

- **src/Entity/** : Contient les classes de données réutilisables qui mappent la base de données (Category.php, Order.php, OrderItem.php, Product.php, User.php).

- **src/Controller/** : Gère la logique des routes utilisateur et de l'affichage (dont Admin/ qui abrite la configuration d'EasyAdmin).

- **src/Repository/** : Contient les requêtes personnalisées pour récupérer les données de la BDD.

- **templates/** : Contient les vues du site web écrites avec le moteur de template Twig.

- **config/** : Regroupe tous les fichiers de configuration de l'application (sécurité, packages, routes).

---

## Sécurité

Le framework Symfony prend en charge nativement les aspects critiques de la sécurité de l'application :

- **Authentification et Autorisation :** Gestion stricte des rôles (ex: accès au dossier /admin restreint aux utilisateurs possédant le rôle ROLE_ADMIN).

- **Hachage des mots de passe :** Géré automatiquement par le composant Security de Symfony à l'aide des algorithmes de hachage recommandés et sécurisés les plus récents (comme le Password Hasher par défaut).

- **Protection CSRF :** Intégrée automatiquement sur l'ensemble des formulaires générés par Symfony afin d'empêcher les attaques par falsification de requête intersites.

- **Injections SQL & XSS :** Doctrine utilise systématiquement des requêtes préparées, et le moteur Twig applique un échappement automatique de toutes les variables afin de neutraliser tout risque d'attaque par injection.

---

## Auteur

Projet réalisé par Rayan Dafi — développement web.
