<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    // On injecte le service qui permet de hacher (crypter) les mots de passe
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // L'ID est généré automatiquement, on le cache dans le formulaire
            IdField::new('id')->hideOnForm(),
            
            EmailField::new('email', 'Adresse Email'),
            
            TextField::new('first_name', 'Prénom'),
            
            TextField::new('last_name', 'Nom'),
            
            // Le mot de passe : on l'affiche uniquement dans le formulaire (création/édition), 
            // jamais dans le tableau récapitulatif (pour la sécurité)
            TextField::new('password', 'Mot de passe')
                ->onlyOnForms(),
        ];
    }

    /**
     * Cette méthode est appelée automatiquement par EasyAdmin AVANT de sauvegarder
     * un NOUVEL utilisateur dans la base de données.
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Si l'entité sauvegardée est bien un User, et qu'un mot de passe a été tapé
        if ($entityInstance instanceof User && $entityInstance->getPassword()) {
            
            // On hache le mot de passe tapé en clair
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            
            // On remplace le texte clair par le hash sécurisé
            $entityInstance->setPassword($hashedPassword);
        }
        
        // On laisse EasyAdmin faire la sauvegarde finale
        parent::persistEntity($entityManager, $entityInstance);
    }
}