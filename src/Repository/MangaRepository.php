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
}
