<?php

namespace App\Repository;

use App\Entity\ContentHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ContentHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentHistory[]    findAll()
 * @method ContentHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentHistory::class);
    }

    // /**
    //  * @return ContentHistory[] Returns an array of ContentHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContentHistory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
