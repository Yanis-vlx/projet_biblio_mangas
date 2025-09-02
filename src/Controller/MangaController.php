<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MangaRepository;
use App\Entity\Manga;
use Symfony\Component\HttpFoundation\Request;


final class MangaController extends AbstractController
{
    #[Route('/manga', name: 'app_manga_index', methods: ['GET'])]
    public function index(Request $request, MangaRepository $mangaRepository): Response
    {


        $genre = $request->query->get('genre'); // string ou null
        $order = $request->query->get('order', 'ASC'); // ASC par défaut

       
        $mangas = $mangaRepository->findByGenreAndOrder($genre, $order);
        
        return $this->render('manga/index.html.twig', [
            'mangas' => $mangas,
        ]);

        
    }

    #[Route('/manga/{id}', name: 'app_manga_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Manga $manga): Response
    {
        return $this->render('manga/show.html.twig', [
            'manga' => $manga,
        ]);
    }
}
