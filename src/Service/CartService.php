<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository
    ) {
    }

    /**
     * Ajouter un produit au panier ou augmenter sa quantité
     */
    public function add(int $id): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('panier', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('panier', $cart);
    }

    /**
     * Diminuer la quantité d'un produit ou le supprimer s'il n'en reste qu'un
     */
    public function decrease(int $id): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('panier', []);

        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        $session->set('panier', $cart);
    }

    /**
     * Supprimer complètement un produit du panier (peu importe la quantité)
     */
    public function remove(int $id): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('panier', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('panier', $cart);
    }

    /**
     * Vider entièrement le panier
     */
    public function clear(): void
    {
        $this->requestStack->getSession()->remove('panier');
    }

    /**
     * Récupérer le panier avec les objets produits complets et les quantités
     */
    public function getFullCart(): array
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('panier', []);

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            
            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartWithData;
    }

    /**
     * Calculer le prix total TTC du panier
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getFullCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }
}