<?php

namespace App\Controller\Admin;

// Importation de tes entités (bien que facultatives maintenant pour le menu, on les garde si besoin)
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;

// NOUVEAU : Importation obligatoire de tes contrôleurs CRUD pour EasyAdmin 5
use App\Controller\Admin\CategoryCrudController;
use App\Controller\Admin\OrderCrudController;
use App\Controller\Admin\ProductCrudController;
use App\Controller\Admin\UserCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // On récupère le générateur d'URL d'EasyAdmin
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        
        // On génère l'URL pour afficher la liste des produits
        $url = $adminUrlGenerator->setController(ProductCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('R Tech');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        
        // --- NOUVELLE SYNTAXE EASYADMIN 5 ---
        // L'ordre est maintenant : Contrôleur CRUD, Label, Icône
        yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fas fa-users');
        yield MenuItem::linkTo(CategoryCrudController::class, 'Catégories', 'fas fa-tags');
        yield MenuItem::linkTo(ProductCrudController::class, 'Produits', 'fas fa-box');
        yield MenuItem::linkTo(OrderCrudController::class, 'Commandes', 'fas fa-shopping-cart');
    }
}