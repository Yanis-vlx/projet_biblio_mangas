<?php

namespace App\Controller;

use App\Entity\Manga;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishlistController extends AbstractController
{
    #[Route('/favorite/add/{id}', name: 'favorite_add')]
    public function add(Manga $manga, EntityManagerInterface $em): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!$user->getFavoriteMangas()->contains($manga)) {
            $user->addFavoriteManga($manga);
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('app_manga_show', ['id' => $manga->getId()]);
    }

    #[Route('/favorite/remove/{id}', name: 'favorite_remove')]
    public function remove(Manga $manga, EntityManagerInterface $em): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getFavoriteMangas()->contains($manga)) {
            $user->removeFavoriteManga($manga);
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('app_manga_show', ['id' => $manga->getId()]);
    }

    #[Route('/favorite/list', name: 'favorite_list')]
    public function list(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $favorites = $user->getFavoriteMangas();

        return $this->render('profile/index.html.twig', [
            'favorites' => $favorites
        ]);
    }
}
