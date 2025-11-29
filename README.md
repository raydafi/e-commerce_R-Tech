## Table des matières

- Description
- Fonctionnalités
- Technologies
- Prérequis
- Installation locale
- Configuration
- Base de données
- Démarrage
- Tests
- Endpoints / Utilisation
- Déploiement
- Structure du projet
- Contribution
- Contact

---

## Description

e-commerce_R-Tech est une application e‑commerce développée en PHP. Elle permet de gérer un catalogue de produits, un panier, les commandes et le paiement (voir configuration du module paiement). Ce README décrit comment installer, configurer et lancer le projet en local ainsi que les bonnes pratiques pour le maintenir.

## Fonctionnalités principales

- Catalogue produits (CRUD)
- Parcours client : ajout au panier, modification, validation de commande
- Gestion des utilisateurs (inscription / connexion / profil)
- Gestion des commandes (statuts, historique)
- Intégration d'un paiement externe (ex. Stripe / PayPal) — config à adapter
- Espace administration pour gérer produits, catégories et commandes
- API REST (si applicable) pour intégration front-end ou mobile

## Technologies

- Langage : PHP (100%)
- [Optionnel] Framework : [Laravel / Symfony / autre] — remplacer par le framework utilisé
- Base de données : MySQL / MariaDB (recommandé)
- Outils : Composer pour la gestion des dépendances

## Prérequis

- PHP >= 8.0 (ou version requise par le framework)
- Composer
- MySQL / MariaDB (ou autre SGBD configuré)
- Node.js & npm/yarn (si assets front-end à compiler)
- Extensions PHP courantes : pdo, mbstring, openssl, tokenizer, xml, ctype, json

## Installation locale

1. Clonez le dépôt
   git clone https://github.com/raydafi/e-commerce_R-Tech.git
   cd e-commerce_R-Tech

2. Installez les dépendances PHP
   composer install

3. Installez les dépendances front-end (si présentes)
   npm install
   npm run dev   # ou npm run build

4. Créez le fichier d'environnement :
   - Copier .env.example vers .env
   - Remplir les valeurs (voir section Variables d'environnement)

## Configuration

Remplissez le fichier `.env` avec vos valeurs locales. Exemple de variables importantes :

- APP_NAME=ECommerceRTech
- APP_ENV=local
- APP_DEBUG=true
- APP_URL=http://localhost

- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=ecommerce_db
- DB_USERNAME=root
- DB_PASSWORD=secret

- MAIL_HOST=smtp.mailtrap.io
- MAIL_PORT=2525
- MAIL_USERNAME=null
- MAIL_PASSWORD=null
- MAIL_FROM_ADDRESS=contact@example.com
- MAIL_FROM_NAME="E-Commerce R-Tech"

- PAYMENT_GATEWAY=stripe
- STRIPE_KEY=pk_test_...
- STRIPE_SECRET=sk_test_...

Adaptez cette liste selon les variables réellement utilisées par votre projet.

## Base de données

1. Créez la base de données :
   - MySQL : CREATE DATABASE ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

2. Exécutez les migrations (si le projet utilise des migrations) :
   - Laravel : php artisan migrate
   - Symfony / Doctrine : php bin/console doctrine:migrations:migrate
   - Sinon : importer le fichier SQL fourni (ex. database/schema.sql)

3. Chargez les jeux de données de test (seeders) :
   - Laravel : php artisan db:seed
   - Symfony : php bin/console doctrine:fixtures:load
   - Ou importer database/seed.sql

## Démarrage (en local)

- Avec un framework :
  - Laravel : php artisan serve --host=127.0.0.1 --port=8000
  - Symfony : symfony server:start
- Sans framework : configurer votre serveur web (Apache / Nginx) pour pointer sur le dossier `public/` ou `web/`.

Ensuite, ouvrez : http://localhost:8000 (ou l'URL configurée)

## Tests

- Tests unitaires / fonctionnels (exemples) :
  - PHPUnit : ./vendor/bin/phpunit
  - Pour exécuter une suite spécifique : ./vendor/bin/phpunit tests/Feature

Documentez les commandes de test réelles et ajoutez des CI (GitHub Actions) si nécessaire.

## Endpoints / Utilisation

Voici des exemples génériques d'API REST (adapter aux routes réelles) :

- GET /api/products — lister les produits
- GET /api/products/{id} — détail produit
- POST /api/cart — ajouter au panier
- GET /api/cart — afficher panier
- POST /api/checkout — effectuer le paiement
- POST /api/auth/register — création de compte
- POST /api/auth/login — authentification

Inclure la documentation Swagger/OpenAPI si disponible.

## Sécurité et bonnes pratiques

- Ne pas committer des secrets (.env) dans le dépôt.
- Utiliser HTTPS en production.
- Mettre en place des validations côté serveur pour tout input utilisateur.
- Limiter le nombre d'essais de connexion et configurer la protection contre CSRF/XSS.

## Déploiement

- Préparer un environnement de production (PHP, composer, base de données)
- Mettre les variables d'environnement en production
- Exécuter les migrations et seeders en mode production
- Configurer la mise en cache (opcache, cache d'application)
- Configurer la sauvegarde régulière de la base de données
- Recommandation : déploiement via CI/CD (GitHub Actions / GitLab CI / autre)

Exemple simplifié de workflow :
1. push sur la branche main
2. CI exécute les tests
3. si OK, pipeline déploie sur serveur (SSH / rsync / container)

## Structure du projet (exemple)

- app/               — logique applicative (controllers, services)
- config/            — configuration
- public/            — point d'entrée web (index.php)
- resources/         — vues / assets
- routes/            — routes de l'application
- database/          — migrations / seeders / schema.sql
- tests/             — tests unitaires et fonctionnels
- vendor/            — dépendances (composer)

Adaptez cette section à la structure réelle du dépôt.

## Contribution

Merci pour votre intérêt ! Pour contribuer :

1. Forkez le dépôt
2. Créez une branche feature/mon-changement
3. Faites vos modifications et tests
4. Ouvrez une Pull Request avec une description claire

Respectez le style de code, ajoutez des tests pour tout nouveau comportement et documentez les modifications.

## Issues et roadmap

- Ouvrez des issues pour signaler les bugs et proposer des fonctionnalités.
- Utilisez des labels clairs (bug, enhancement, docs, help wanted).
- Documentez la roadmap dans le projet ou le fichier ROADMAP.md.


## Contact

Pour toute question, contacter : rayan.dafi@outlook.com
