<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
// N'oublie pas ces imports pour les champs !
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du produit'),
            TextareaField::new('description', 'Description'),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),
            AssociationField::new('category', 'Catégorie'),
            
            // LE FAMEUX CHAMP IMAGE :
            ImageField::new('image', 'Image du produit')
                ->setBasePath('uploads/images/products') // Où EasyAdmin doit chercher l'image pour l'afficher
                ->setUploadDir('public/uploads/images/products') // Où EasyAdmin doit sauvegarder le fichier
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Génère un nom de fichier unique et sécurisé
                ->setRequired(false), // N'oblige pas à mettre une image à chaque fois
        ];
    }
}