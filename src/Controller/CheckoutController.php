<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function index(CartService $cartService, EntityManagerInterface $em): Response
    {
        // 1. Sécurité : il faut être connecté pour commander
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartService->getFullCart();
        if (empty($cart)) {
            return $this->redirectToRoute('app_home');
        }

        // 2. Création de l'entité Order
        $order = new Order();
        $order->setUser($user); // On lie l'utilisateur connecté
        $order->setDate(new \DateTime());
        $order->setTime(new \DateTime());
        $order->setAmount($cartService->getTotal());

        // 3. Création des OrderItems (le détail de la commande)
        foreach ($cart as $item) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order);
            $orderItem->setProduct($item['product']);
            // Note : Si tu as un champ quantité dans OrderItem, ajoute-le ici :
            // $orderItem->setQuantity($item['quantity']); 

            $em->persist($orderItem);
        }

        $em->persist($order);
        $em->flush(); // On envoie tout en base de données d'un coup

        // 4. On vide le panier après la commande
        // Il faudra ajouter une méthode clear() dans ton CartService (voir plus bas)
        $cartService->clear();

        return $this->render('checkout/success.html.twig', [
            'order' => $order
        ]);    
    }
}