<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MangaRepository;
use App\Entity\Manga;
use Symfony\Component\HttpFoundation\Request;
use App\Model\SearchData;
use App\Form\SearchType;
<<<<<<< HEAD
=======
use App\Form\MangaType;
use Doctrine\ORM\EntityManagerInterface;
>>>>>>> 7725b0c3ef619896f07c7ee44ee1ac2e401a68f0


final class MangaController extends AbstractController
{
    #[Route('/manga', name: 'app_manga_index', methods: ['GET'])]
    public function index(Request $request, MangaRepository $mangaRepository): Response
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

        return $this->render('manga/index.html.twig', [
            'form' => $form->createView(),
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
<<<<<<< HEAD
=======

     #[Route('admin/manga/new', name: 'app_admin_manga_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $author = new Manga();
        $form = $this->createForm(MangaType::class, $author);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($author);
            $manager->flush();

            return $this->redirectToRoute('app_admin_manga_new');
        }
        
        return $this->render('manga/new.html.twig', [
            'form' => $form,
        ]);
    }
>>>>>>> 7725b0c3ef619896f07c7ee44ee1ac2e401a68f0
}
