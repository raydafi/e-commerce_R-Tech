<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    // On injecte le service pour hacher les mots de passe
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // 1. CRÉATION DE L'ADMINISTRATEUR
        $admin = new User();
        $admin->setEmail('admin@ecommerce.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstName('Jean'); 
        $admin->setLastName('Dupont');
        // Hachage du mot de passe 'password123'
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'password123');
        $admin->setPassword($hashedPassword);
        
        $manager->persist($admin);

        // 2. CRÉATION DES CATÉGORIES
        $categories = [];
        $categoryNames = ['Vêtements', 'Électronique', 'Maison', 'Livres', 'Jouets'];
        
        foreach ($categoryNames as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($faker->paragraph());
            // Si ton entité Category a d'autres champs obligatoires, ajoute-les ici
            $manager->persist($category);
            $categories[] = $category; // On les garde en mémoire pour y associer les produits
        }

        // 3. CRÉATION DES PRODUITS
        for ($i = 0; $i < 30; $i++) {
            $product = new Product();
            $product->setName($faker->words(3, true));
            $product->setDescription($faker->paragraphs(2, true));
            $product->setPrice($faker->randomFloat(2, 5, 500)); // Prix entre 5 et 500
            $product->setQuantity($faker->numberBetween(10, 100));
            $product->setCategory($faker->randomElement($categories));
            
            $manager->persist($product);
        }

        // 4. SAUVEGARDE EN BASE DE DONNÉES
        $manager->flush();
    }
}