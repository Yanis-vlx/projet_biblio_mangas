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
use App\Form\MangaType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Editor;

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

    #[Route('/manga/list', name: 'app_manga_list', methods: ['GET'])]
    public function list(MangaRepository $mangaRepository): Response
    {
        $mangas = $mangaRepository->findAll();
        $form = $this->createForm(SearchType::class); // ajoute le form pour Twig
        return $this->render('manga/list.html.twig', [
            'mangas' => $mangas,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/manga/{id}', name: 'app_manga_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Manga $manga, Request $request): Response
    {
        $from = $request->query->get('from', 'catalogue'); // par défaut 'catalogue'
    
        return $this->render('manga/show.html.twig', [
            'manga' => $manga,
            'from' => $from,
        ]);
    }

    #[Route('admin/manga/{id}/edit', name: 'app_admin_manga_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[Route('admin/manga/new', name: 'app_admin_manga_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, int $id = null): Response
    {
        $manga = $id 
            ? $manager->getRepository(Manga::class)->find($id) 
            : new Manga();

        $form = $this->createForm(MangaType::class, $manga);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($manga);
            $manager->flush();

            return $this->redirectToRoute('app_manga_list');
        }

        return $this->render('manga/new.html.twig', [
            'form' => $form->createView(),
            'manga' => $manga
        ]);
    }

    #[Route('admin/manga/{id}/delete', name: 'app_admin_manga_delete', methods: ['POST'])]
        public function delete(Request $request, Manga $manga, EntityManagerInterface $manager): Response
        {
            if ($this->isCsrfTokenValid('delete'.$manga->getId(), $request->request->get('_token'))) {
                $manager->remove($manga);
                $manager->flush();
            }

            return $this->redirectToRoute('app_manga_list');
        }


}
