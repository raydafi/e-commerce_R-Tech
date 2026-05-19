<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/produits', name: 'app_product_index')]
    public function index(
        ProductRepository $productRepository, 
        CategoryRepository $categoryRepository, 
        Request $request
    ): Response {
        
        $search = $request->query->get('search');
        $categoryId = $request->query->get('category') ? (int) $request->query->get('category') : null;
        $priceMin = $request->query->get('price_min') ? (float) $request->query->get('price_min') : null;
        $priceMax = $request->query->get('price_max') ? (float) $request->query->get('price_max') : null;

        $products = $productRepository->findWithFilters($search, $categoryId, $priceMin, $priceMax);
        $categories = $categoryRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
    
    // Le {slug} ou {id} permet à Symfony de comprendre quel produit chercher
    #[Route('/produit/{id}', name: 'app_product_show')]
    public function show(Product $product): Response
    {
        // Magie de Symfony : en mettant (Product $product) et l'{id} dans l'URL,
        // il va chercher tout seul le bon produit dans la base de données !

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}