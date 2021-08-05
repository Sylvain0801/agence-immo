<?php

namespace App\Repository;

use App\Entity\Offer;
use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function findAllArray(int $limit = null) : array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.property', 'p')
            ->addSelect('p')
            ->leftJoin('p.city', 'c')
            ->addSelect('c')
            // ->join('p.images', 'i')
            // ->join('i.property', 'ip')
            // ->where('ip.id = p.id')
            // ->addSelect('i')
            ->orderBy('o.updated_at', 'DESC');

        if ($limit) $qb->setMaxResults($limit);

        $query = $qb->getQuery();
        return $query->execute();
    }

    // /**
    //  * @return Offer[] Returns an array of Offer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offer
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
