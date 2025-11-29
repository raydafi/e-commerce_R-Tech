# 📱 R-Tech — E-commerce de Produits Reconditionnés

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

R-Tech est une application web e-commerce développée en PHP natif (sans framework), spécialisée dans la vente de produits Apple reconditionnés (iPhone, MacBook, iPad, Apple Watch).

---

## Sommaire
- [Fonctionnalités](#fonctionnalités)
- [Installation](#installation)
  - [Prérequis](#prérequis)
  - [Cloner le projet](#cloner-le-projet)
  - [Configuration de la base de données](#configuration-de-la-base-de-données)
  - [Configuration PHP](#configuration-php)
  - [Lancement](#lancement)
- [Structure du projet](#structure-du-projet)
- [Sécurité](#sécurité)
- [Auteur](#auteur)
- [Contribution](#contribution)
- [Licence](#licence)

---

## 🚀 Fonctionnalités

### 👤 Pour les utilisateurs
- Authentification : Inscription, Connexion (avec Captcha), Déconnexion.
- Gestion du compte : modification du profil, réinitialisation du mot de passe via token par email.
- Catalogue :
  - Recherche de produits par nom.
  - Filtres (prix min/max, type, état).
  - Système de notation (étoiles).
- Panier : ajout/suppression d'articles, calcul automatique du total.
- Favoris : ajout/retrait dynamique (AJAX) sans rechargement de page.
- Commande : simulation de paiement (Carte / PayPal) et confirmation par email.

### 🛠️ Pour les administrateurs
- Dashboard : vue d'ensemble des produits.
- Gestion des produits (CRUD) : ajouter (upload d'image), modifier, supprimer.

---

## ⚙️ Installation

### Prérequis
- Serveur local (XAMPP, WAMP, MAMP) ou serveur web avec PHP 7.4+.
- MySQL ou MariaDB.
- Composer n'est pas requis (projet en PHP natif).

### Cloner le projet
```bash
git clone https://github.com/raydafi/e-commerce_R-Tech.git
cd e-commerce_R-Tech
```

### Configuration de la base de données
Créez une base de données (ex. `bdd`) et importez le schéma suivant :

```sql
CREATE DATABASE IF NOT EXISTS bdd;
USE bdd;

-- Table Utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Table Produits
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image LONGBLOB,
    type VARCHAR(50),
    etat VARCHAR(50),
    memoire VARCHAR(50),
    detail TEXT
);

-- Table Favoris
-- Remarque : user_id est VARCHAR pour correspondre au username utilisé dans la session
CREATE TABLE favoris (
    user_id VARCHAR(255),
    product_id INT,
    PRIMARY KEY (user_id, product_id)
);

-- Table Commandes
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    products_id INT,
    total_price DECIMAL(10,2),
    status TINYINT DEFAULT 0,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    email_order TINYINT DEFAULT 0
);

-- Table Avis / Notes
CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    product_id INT,
    rating INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Réinitialisation Mot de passe
CREATE TABLE password_resets (
    email VARCHAR(255),
    token VARCHAR(255),
    expires_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

> Astuce : utilisez phpMyAdmin ou la ligne de commande MySQL pour importer ce SQL.

### Configuration PHP
Ouvrez le fichier `bdd.php` (ou votre fichier de configuration DB) et adaptez les identifiants :

```php
<?php
$servername = "localhost";
$dbname = "bdd";       // Nom de la BDD
$dbusername = "root";  // Utilisateur SQL
$dbpassword = "";      // Mot de passe SQL
?>
```

Assurez-vous que les extensions PHP nécessaires sont activées (PDO, pdo_mysql).

### Lancement
Placez le dossier du projet dans le dossier racine de votre serveur local (ex. `htdocs` pour XAMPP) puis rendez-vous sur :
http://localhost/e-commerce_R-Tech/index.php

---

## 📂 Structure du projet (principaux fichiers)
- index.php : page d'accueil
- produits.php : catalogue principal avec filtres
- detail.php : page de détail d'un produit
- cart.php : gestion du panier
- favoris.php & add_to_favorite.php : gestion et logique AJAX des favoris
- dashboard.php : panneau d'administration
- connexion.php / inscription.php : pages d'authentification
- image.php : rendu des images stockées en BLOB
- bdd.php : configuration de la connexion à la base de données

---

## 🛡️ Sécurité
Le projet met en œuvre plusieurs bonnes pratiques :
- Mots de passe : hashés avec password_hash(), vérifiés via password_verify().
- Requêtes : utilisation de requêtes préparées (PDO::prepare) pour éviter les injections SQL.
- XSS : échappement des sorties avec htmlspecialchars().
- Sessions : gestion des sessions PHP pour l'état utilisateur.
- (À améliorer) : validation côté serveur et côté client des données entrantes, rate limiting, protections CSRF pour les formulaires sensibles.

---

## 📝 Auteur
Projet réalisé par Raydafi — développement web.

---

## 🤝 Contribution
Contributions et retours bienvenus :
- Ouvrez une issue pour signaler un bug ou proposer une amélioration.
- Proposez une PR pour corriger/ajouter une fonctionnalité.

---

Si vous souhaitez que je pousse ce README amélioré directement dans le dépôt, je peux créer une branche (par ex. `fix/readme`) et proposer un commit/PR — dites-moi si je dois le faire et quel nom de branche utiliser.
