<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MangaRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PayController extends AbstractController
{
    private string $clientSecret;
    public function __construct(string $clientSecret){

         $this->clientSecret = $clientSecret;
    }    
    
    #[Route('/pay', name: 'app_pay')]
    public function startPayment(SessionInterface $session, MangaRepository $mangaRepository): Response
    {

        $cart = $session->get('cart', []);

        $lineItems = [];

        foreach ($cart as $id => $quantity) {
            $manga = $mangaRepository->find($id);
            if($manga){
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur', 
                        'product_data' => [
                            'name' => $manga->getTitle(),
                        ],
                        'unit_amount' => (int) round($manga->getPrix() * 100), 
                    ],
                    'quantity' => $quantity, 
                ];
            }    
    }

    if (empty($lineItems)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
        }

        // Configurer Stripe
        Stripe::setApiKey($this->clientSecret);

        // Créer la session de paiement
        $checkoutSession = StripeSession::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'billing_address_collection' => 'required',
            'shipping_address_collection' => [
                'allowed_countries' => ['FR']
            ]
        ]);

        return $this->redirect($checkoutSession->url, 303);

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
