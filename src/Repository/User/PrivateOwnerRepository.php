<?php

namespace App\Repository\User;

use App\Entity\User\PrivateOwner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrivateOwner|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrivateOwner|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrivateOwner[]    findAll()
 * @method PrivateOwner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivateOwnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrivateOwner::class);
    }

    // /**
    //  * @return PrivateOwner[] Returns an array of PrivateOwner objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrivateOwner
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
