<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoryRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        // On récupère uniquement les 4 derniers produits ajoutés
        $latestProducts = $productRepository->findBy([], ['id' => 'DESC'], 4);

        return $this->render('home/index.html.twig', [
            'products' => $latestProducts,
        ]);
    }

    public function menuCategories(CategoryRepository $categoryRepository): Response
    {
        return $this->render('home/_menu_categories.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }
}