<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/commande/paiement/{id}', name: 'app_stripe_checkout')]
    public function checkout($id, EntityManagerInterface $em, UrlGeneratorInterface $generator): Response
    {
        // 1. On récupère la commande dans la base de données
        $order = $em->getRepository(Order::class)->find($id);

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        // 2. On initialise Stripe avec la clé secrète stockée dans le fichier .env
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // 3. On crée la session de paiement Stripe
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Commande n°' . $order->getId(),
                    ],
                    // Stripe prend les montants en centimes ! Donc on multiplie par 100
                    'unit_amount' => (int) ($order->getAmount() * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            // Où rediriger si le paiement réussit ?
            'success_url' => $generator->generate('app_stripe_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            // Où rediriger si le client annule ?
            'cancel_url' => $generator->generate('app_stripe_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        // 4. On redirige le client vers la page de paiement Stripe
        return $this->redirect($checkout_session->url, 303);
    }

    #[Route('/commande/succes', name: 'app_stripe_success')]
    public function success(): Response
    {
        return $this->render('stripe/success.html.twig');
    }

    #[Route('/commande/erreur', name: 'app_stripe_cancel')]
    public function cancel(): Response
    {
        return $this->render('stripe/cancel.html.twig');
    }
}