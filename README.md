
````
# 📱 R-Tech - E-commerce de Produits Reconditionnés

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

**R-Tech** est une application web e-commerce développée en PHP natif (sans framework), spécialisée dans la vente de produits Apple reconditionnés (iPhone, MacBook, iPad, Apple Watch). Le projet met en avant une gestion complète des utilisateurs, un catalogue dynamique et un panneau d'administration.

---

## 🚀 Fonctionnalités

### 👤 Pour les Utilisateurs
* **Authentification :** Inscription, Connexion (avec Captcha), Déconnexion.
* **Gestion de compte :** Modification du profil, réinitialisation de mot de passe par token email.
* **Catalogue :**
    * Recherche de produits par nom.
    * Filtres avancés (Prix min/max, Type, État).
    * Système de notation (étoiles).
* **Panier :** Ajout/Suppression d'articles, calcul automatique du total.
* **Favoris :** Ajout/Retrait dynamique (AJAX) sans rechargement de page.
* **Commande :** Simulation de paiement (Carte Bancaire / PayPal) et confirmation par email.

### 🛠️ Pour les Administrateurs
* **Dashboard :** Vue d'ensemble des produits.
* **Gestion des produits (CRUD) :**
    * Ajouter un produit (avec upload d'image).
    * Modifier les informations.
    * Supprimer un produit.

---

## ⚙️ Installation

### 1. Prérequis
* Un serveur local (XAMPP, WAMP, MAMP) ou un serveur web avec PHP 7.4+.
* MySQL ou MariaDB.

### 2. Cloner le projet
```bash
git clone [https://github.com/votre-username/R-Tech.git](https://github.com/votre-username/R-Tech.git)
cd R-Tech
````

### 3\. Configuration de la Base de Données

Créez une base de données nommée `bdd` et importez le schéma SQL suivant :

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
-- Note : user_id stocke ici le username (VARCHAR) pour correspondre à la session PHP
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

### 4\. Configuration PHP

Ouvrez le fichier `bdd.php` et modifiez les identifiants si nécessaire :

```php
<?php
$servername = "localhost";
$dbname = "bdd";       // Nom de votre BDD
$dbusername = "root";  // Votre utilisateur SQL
$dbpassword = "";      // Votre mot de passe SQL
?>
```

### 5\. Lancement

Placez les fichiers dans le dossier `htdocs` (XAMPP) ou `www` (WAMP) et accédez à :
`http://localhost/R-Tech/index.php`

-----

## 📂 Structure du Projet

  * `index.php` : Page d'accueil (Landing page).
  * `produits.php` : Catalogue principal avec filtres.
  * `detail.php` : Page détail d'un produit.
  * `cart.php` : Gestion du panier.
  * `favoris.php` & `add_to_favorite.php` : Gestion et logique AJAX des favoris.
  * `dashboard.php` : Panneau d'administration.
  * `connexion.php` / `inscription.php` : Authentification.
  * `image.php` : Script de rendu des images stockées en BLOB.

-----

## 🛡️ Sécurité

Le projet implémente plusieurs mesures de sécurité de base :

  * **Mots de passe :** Hashage via `password_hash()` et vérification via `password_verify()`.
  * **Injections SQL :** Utilisation systématique de requêtes préparées (`PDO::prepare`).
  * **XSS :** Échappement des sorties avec `htmlspecialchars()`.
  * **Session :** Gestion des sessions PHP pour l'état utilisateur.

-----

## 📝 Auteur

Projet réalisé dans le cadre d'un développement web PHP.

```
```
