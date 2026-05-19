<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(OrderRepository $orderRepository): Response
    {
        // 1. On récupère l'utilisateur actuellement connecté
        $user = $this->getUser();

        // 2. Sécurité : s'il n'est pas connecté, on le renvoie vers la page de login
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // 3. On demande au Repository de trouver les commandes de cet utilisateur
        // Le deuxième paramètre ['id' => 'DESC'] permet de trier de la plus récente à la plus ancienne
        $orders = $orderRepository->findBy(
            ['user' => $user],
            ['id' => 'DESC']
        );

        // 4. On envoie les commandes à la vue Twig
        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/compte/modifier', name: 'app_account_edit')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // On crée le formulaire en lui passant l'utilisateur actuel
        $form = $this->createForm(ProfileType::class, $user);

        // On dit au formulaire d'écouter la requête (les données envoyées par le bouton submit)
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide...
        if ($form->isSubmitted() && $form->isValid()) {
            // ... on sauvegarde dans la base de données
            $em->flush();

            // On ajoute un petit message flash de succès
            $this->addFlash('success', 'Vos informations ont bien été mises à jour !');

            // On redirige vers l'accueil du compte
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}