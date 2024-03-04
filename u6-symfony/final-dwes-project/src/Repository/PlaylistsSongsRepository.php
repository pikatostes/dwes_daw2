<?php

namespace App\Repository;

use App\Entity\PlaylistsSongs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlaylistsSongs>
 *
 * @method PlaylistsSongs|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlaylistsSongs|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlaylistsSongs[]    findAll()
 * @method PlaylistsSongs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistsSongsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlaylistsSongs::class);
    }

//    /**
//     * @return PlaylistsSongs[] Returns an array of PlaylistsSongs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PlaylistsSongs
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
