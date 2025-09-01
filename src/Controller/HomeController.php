<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HomeController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        $response = $this->client->request(
            'GET',
            'https://api.jikan.moe/v4/top/manga?limit=5'
        );

        $data = $response->toArray();

        $mangas = array_map(function ($manga) {
            return [
                'title' => $manga['title'],
                'image' => $manga['images']['jpg']['large_image_url'],
            ];
        }, $data['data']);

        return $this->render('main/main.html.twig', [
            'mangas' => $mangas,
        ]);
    }
}

