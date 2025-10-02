<?php

namespace App\Repository;

use App\Entity\Manga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Model\SearchData;


/**
 * @extends ServiceEntityRepository<Manga>
 */
class MangaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Manga::class);
    }

    public function findByGenreAndOrder(?string $genre, string $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder('m');

        if ($genre) {
            $qb->andWhere('m.genre = :genre')
            ->setParameter('genre', $genre);
        }

        $qb->orderBy('m.title', $order);

        return $qb->getQuery()->getResult();
    }

    public function findBySearch(SearchData $searchData): array
    {
        $qb = $this->createQueryBuilder('m');

        if (!empty($searchData->q)) {
        $qb->andWhere('m.title LIKE :q')
           ->setParameter('q', '%' . $searchData->q . '%');
            }

        return $qb->getQuery()->getResult();
    }

    public function findRandomMangas(int $limit = 6): array
    {
        return $this->createQueryBuilder('m')
            ->addSelect('RAND() as HIDDEN rand')
            ->orderBy('rand')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findSimilarMangas(Manga $manga, int $limit = 5): array
    {
        // 1️⃣ Mangas dont le titre est similaire
        $similarByTitle = $this->createQueryBuilder('m')
            ->where('m.id != :id')
            ->andWhere('m.title LIKE :title')
            ->setParameter('id', $manga->getId())
            ->setParameter('title', '%'.$manga->getTitle().'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        // Si on a déjà assez, on retourne
        if (count($similarByTitle) >= $limit) {
            return array_slice($similarByTitle, 0, $limit);
        }

        // 2️⃣ Compléter avec mangas du même genre (si genre non nul)
        $similarByGenre = [];
        if ($manga->getGenre() !== null) {
            $similarByGenre = $this->createQueryBuilder('m')
                ->where('m.genre = :genre')
                ->andWhere('m.id != :id')
                ->setParameter('genre', $manga->getGenre())
                ->setParameter('id', $manga->getId())
                ->setMaxResults($limit - count($similarByTitle))
                ->getQuery()
                ->getResult();
        }

        // Combiner les deux listes
        return array_merge($similarByTitle, $similarByGenre);
    }

}
