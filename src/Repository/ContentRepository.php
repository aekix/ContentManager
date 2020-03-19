<?php

namespace App\Repository;

use App\Entity\Content;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Content|null find($id, $lockMode = null, $lockVersion = null)
 * @method Content|null findOneBy(array $criteria, array $orderBy = null)
 * @method Content[]    findAll()
 * @method Content[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Content::class);
    }

    public function findHomePublishedContents()
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.enabled = 1')
            ->andWhere('c.status = 1')
            ->andwhere('c.publisher IS NOT NULL')
            ->orderBy('c.publicationDate', 'DESC')
            ->setMaxResults(10);
        return $qb->getQuery()->execute();
    }

    public function findPublishedContentsFromUser(User $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.author = ' . $user->getId())
            ->andWhere('c.enabled = 1')
            ->andWhere('c.status = 1')
            ->andWhere('c.publisher IS NOT NULL')
            ->andWhere('c.publicationDate IS NOT NULL')
            ->orderBy('c.publicationDate', 'DESC');
        return $qb->getQuery()->execute();
    }

    public function findReviewContentsFromUser(User $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.author = ' . $user->getId())
            ->andWhere('c.enabled = 1')
            ->andWhere('c.status = 1')
            ->andWhere('c.publisher IS NULL')
            ->andWhere('c.publicationDate IS NULL');
        return $qb->getQuery()->execute();
    }

        public function findPublishedContents()
    {
        $qb = $this->createQueryBuilder('c')
            ->andwhere('c.enabled = 1')
            ->andwhere('c.status = 1')
            ->andwhere('c.publisher IS NOT NULL')
            ->andwhere('c.publicationDate IS NOT NULL')
            ->orderBy('c.publicationDate', 'DESC');

        return $qb->getQuery()->execute();
    }

    public function findDraftsContentsFromUser(User $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.author = ' . $user->getId())
            ->andWhere('c.enabled = 1')
            ->andWhere('c.status = 0')
            ->andWhere('c.publisher IS NULL')
            ->andWhere('c.publicationDate IS NULL');

        return $qb->getQuery()->execute();
    }


    // /**
    //  * @return Content[] Returns an array of Content objects
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
    public function findOneBySomeField($value): ?Content
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
