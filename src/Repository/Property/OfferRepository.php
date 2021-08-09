<?php

namespace App\Repository\Property;

use App\Entity\Property\Offer;
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
            ->orderBy('o.updated_at', 'DESC');

        if ($limit) $qb->setMaxResults($limit);

        $query = $qb->getQuery();
        return $query->execute();
    }

    
    public function findOfferCitiesCoord() : array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.property', 'p')
            ->leftJoin('p.city', 'c')
            ->distinct()
            ->select('c.id', 'c.coordinates');

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function searchOfferByCriteria(
        array $listCitiesId = null,
        int $sell = null,
        int $priceMin = null,
        int $priceMax = null,
        int $rent = null,
        int $rentMin = null,
        int $rentMax = null,
        int $rooms = null,
        int $type = null,
        int $furnished = null
        ): array
    {
        $qb = $this->createQueryBuilder('o');

        if ($listCitiesId != null) {
            $qb
            ->leftJoin('o.property', 'p')
            ->leftJoin('p.city', 'c')
            ->where('c.id IN(:listCitiesId)')
            ->setParameter('listCitiesId', array_keys($listCitiesId));
        }
        if ($sell != null) {
            $qb->andWhere('p.transaction_type = :sell')
                ->setParameter('sell', 'vente');
        }
        if ($priceMin != null) {
            $qb->andWhere('p.price >= :priceMin')
                ->setParameter('priceMin', $priceMin);
        }
        if ($priceMax != null) {
            $qb->andWhere('p.price <= :priceMax')
                ->setParameter('priceMax', $priceMax);
        }
        if ($rent != null) {
            $qb->andWhere('p.transaction_type = :rent')
            ->setParameter('rent', 'location');
        }
        if ($rentMin != null) {
            $qb->andWhere('p.price >= :rentMin')
                ->setParameter('rentMin', $rentMin);
        }
        if ($rentMax != null) {
            $qb->andWhere('p.price <= :rentMax')
                ->setParameter('rentMax', $rentMax);
        }
        if ($rooms != null) {
            $qb->andWhere('p.rooms = :rooms')
                ->setParameter('rooms', $rooms);
        }
        if ($type != null) {
            $qb->leftJoin('p.property_type', 't')
                ->andWhere('t.id = :type')
                ->setParameter('type', $type);
        }
        if ($furnished != null) {
            $qb->leftJoin('p.options', 'opt')
                ->andWhere('opt.name LIKE :furnished')
                ->setParameter('furnished', '%meublé%');
        }
        
        $qb->orderBy('o.updated_at', 'DESC');

        return $qb->getQuery()->execute();
    }

    public function findFavorites(array $liste = null) : array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.property', 'p')
            ->where('p.id IN(:listFavoritesId)')
            ->setParameter('listFavoritesId', array_values($liste));

        return $qb->getQuery()->execute();
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
