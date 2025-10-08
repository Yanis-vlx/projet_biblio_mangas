<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MangaRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PayController extends AbstractController
{
    #[Route('/pay', name: 'app_pay')]
    public function startPayment(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
        }

        // On redirige vers la page de simulation de paiement
        return $this->render('cart/simulated_checkout.html.twig', [
            'cart' => $cart
        ]);
    }

    #[Route('/pay/submit', name: 'app_pay_submit', methods:['POST'])]
    public function submitPayment(SessionInterface $session): Response
    {
        $action = $_POST['action'] ?? 'success'; // success ou cancel

        if ($action === 'success') {
            $session->remove('cart');
            return $this->redirectToRoute('payment_success');
        }

        return $this->redirectToRoute('payment_cancel');
    }

    #[Route('/payment/success', name: 'payment_success')]
    public function success(SessionInterface $session): Response
    {
        $session->remove('cart');
        return $this->render('cart/success.html.twig');
    }

    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('cart/cancel.html.twig');
    }
}
