<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Model\SearchData;
use App\Repository\MangaRepository;
use App\Entity\Manga;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchType;

final class HomeController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/', name: 'app_main')]
    public function index(
        MangaRepository $mangaRepository,
        Request $request
    ): Response
    {
       $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mangas = $mangaRepository->findBySearch($searchData);
        } else {
            $genre = $request->query->get('genre'); 
            $order = $request->query->get('order', 'ASC'); 
            $mangas = $mangaRepository->findByGenreAndOrder($genre, $order);
        }

    
        return $this->render('main/main.html.twig', [
            'form' => $form->createView(),
            'mangas' => $mangas,
        ]);
    }
}

