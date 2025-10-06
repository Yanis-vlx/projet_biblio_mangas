<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Manga;
use App\Repository\MangaRepository;

 #[Route('/cart', name: 'cart_')]
final class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, MangaRepository $mangaRepository) {
        $cart = $session->get('cart', []);

        
        $data = [];
        $total = 0;

        foreach($cart as $id => $quantity){
            $manga = $mangaRepository->find($id);

            $data[] = [
                'manga' => $manga,
                'quantity' => $quantity
            ];

            $total += $manga->getPrix() * $quantity;
        }

        return $this->render('cart/index.html.twig', compact('data', 'total'));
    }

   #[Route('/add/{id}', name: 'add')]
    public function add(Manga $manga, SessionInterface $session) {

        //récupérer l'id du produit
        $id = $manga->getId();

        // On récupère le panier existant
        $cart = $session->get('cart', []);


        // On ajoute le produit dans le panier s'il n'y est pas encore
        //Sinon on incrémente

        if(empty($cart[$id])){
            $cart[$id] = 1;
        } else {
            $cart[$id]++;
        }

        $session->set('cart', $cart);
        
        // On redirige vers la page du panier

        return $this->redirectToRoute('cart_index');

    }

     #[Route('/remove/{id}', name: 'remove')]
    public function remove(Manga $manga, SessionInterface $session) {

        //récupérer l'id du produit
        $id = $manga->getId();

        // On récupère le panier existant
        $cart = $session->get('cart', []);


        // On retire le produit du panier s'il n'y a qu'un exemplaire
        //Sinon on décrémente

        if(!empty($cart[$id])){
            if($cart[$id]>1) {
                    $cart[$id]--;
                } else {
                    unset($cart[$id]);
                }
            }

        $session->set('cart', $cart);
        
        // On redirige vers la page du panier

        return $this->redirectToRoute('cart_index');

    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Manga $manga, SessionInterface $session) {

        //récupérer l'id du produit
        $id = $manga->getId();

        // On récupère le panier existant
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
                unset($cart[$id]);
            }

        $session->set('cart', $cart);
        
        // On redirige vers la page du panier

        return $this->redirectToRoute('cart_index');

    }

}
