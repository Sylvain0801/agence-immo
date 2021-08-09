<?php

namespace App\Repository\Faq;

use App\Entity\Faq\CategoryFaq;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryFaq|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryFaq|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryFaq[]    findAll()
 * @method CategoryFaq[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryFaqRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryFaq::class);
    }

    // /**
    //  * @return CategoryFaq[] Returns an array of CategoryFaq objects
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
    public function findOneBySomeField($value): ?CategoryFaq
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
