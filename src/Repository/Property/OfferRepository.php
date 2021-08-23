<?php

namespace App\Repository\Property;

use App\Entity\Property\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function findArrayAllDatas() : array
    {
        $qb = $this->createQueryBuilder('o')
                ->addSelect('o')
                ->leftJoin('o.images', 'i')
                ->addSelect('i')
                ->leftJoin('o.property', 'p')
                ->addSelect('p')
                ->leftJoin('p.city', 'c')
                ->addSelect('c')
                ->leftJoin('p.property_type', 't')
                ->addSelect('t')
                ->leftJoin('p.owner_property', 'ow')
                ->addSelect('ow')
                ->leftJoin('p.manager_property', 'm')
                ->addSelect('m')
                ->leftJoin('p.options', 'opt')
                ->addSelect('opt')
                ->orderBy('o.updated_at', 'DESC');
        
        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function findListByFieldSortAscending(string $field = null) : array
    {
        $qb = $this->createQueryBuilder('o')
            ->select('o.' . $field)
            ->distinct()
            ->orderBy('o.' . $field, 'asc');

        return $qb->getQuery()->execute();
    }

    public function findListByPropertyIdSortAscending() : array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.property', 'p')
            ->select('p.id AS property_id')
            ->distinct()
            ->orderBy('property_id', 'asc');

        return $qb->getQuery()->execute();

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
        $qb = $this
            ->createQueryBuilder('o')
            ->leftJoin('o.images', 'i')
            ->addSelect('i')
            ->leftJoin('o.property', 'p')
            ->addSelect('p')
            ->leftJoin('p.city', 'c')
            ->addSelect('c')
            ->leftJoin('p.property_type', 't')
            ->addSelect('t')
            ->leftJoin('p.owner_property', 'ow')
            ->addSelect('ow')
            ->leftJoin('p.manager_property', 'm')
            ->addSelect('m')
            ->leftJoin('p.options', 'opt')
            ->addSelect('opt');

        if ($listCitiesId != null) {
            $qb
            ->where('c.id IN(:listCitiesId)')
            ->setParameter('listCitiesId', array_keys($listCitiesId));
        }
        if ($sell != null) {
            $qb->andWhere('p.transaction_type = :sell')
                ->setParameter('sell', 'sale');
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
            ->setParameter('rent', 'rental');
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
            $qb->andWhere('t.id = :type')
                ->setParameter('type', $type);
        }
        if ($furnished != null) {
            $qb->andWhere('opt.name LIKE :furnished')
                ->setParameter('furnished', '%meublé%');
        }
        
        $qb->orderBy('o.updated_at', 'DESC');

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function findFavorites(array $liste = null) : array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.property', 'p')
            ->where('p.id IN(:listFavoritesId)')
            ->setParameter('listFavoritesId', array_values($liste));

        return $qb->getQuery()->execute();
    }
    
    public function findListSortedFilteredBycriteria($criterias = null, string $sortBy = 'id', string $order = 'asc') : array
    {
        $qb = $this->createQueryBuilder('o')
                ->addSelect('o')
                ->leftJoin('o.images', 'i')
                ->addSelect('i')
                ->leftJoin('o.property', 'p')
                ->addSelect('p')
                ->leftJoin('p.city', 'c')
                ->addSelect('c')
                ->leftJoin('p.property_type', 't')
                ->addSelect('t')
                ->leftJoin('p.owner_property', 'ow')
                ->addSelect('ow')
                ->leftJoin('p.manager_property', 'm')
                ->addSelect('m')
                ->leftJoin('p.options', 'opt')
                ->addSelect('opt');

        if ($criterias !== null) {
            if ($criterias->get('id') !== null) {
                $qb->andWhere('o.id IN(:idList)')
                    ->setParameter('idList', array_values($criterias->get('id')));
            }
            if ($criterias->get('property_id') !== null) {
                $qb->andWhere('o.property IN(:propertyIdList)')
                    ->setParameter('propertyIdList', array_values($criterias->get('property_id')));
            }
            if ($criterias->get('title') !== null) {
                $qb->andWhere('o.title IN(:titleList)')
                    ->setParameter('titleList', array_values($criterias->get('title')));
            }
            if ($criterias->get('transaction_type') !== null) {
                $qb->andWhere('p.transaction_type IN(:transacList)')
                    ->setParameter('transacList', array_values($criterias->get('transaction_type')));
            }
            if ($criterias->get('city') !== null) {
                $qb->andWhere('c.name IN(:cityList)')
                    ->setParameter('cityList', array_values($criterias->get('city')));
            }
            if ($criterias->get('property_type') !== null) {
                $qb->andWhere('t.name IN(:typeList)')
                    ->setParameter('typeList', array_values($criterias->get('property_type')));
            }
            if ($criterias->get('price') !== null) {
                if (!empty(array_values($criterias->get('price'))[0])) {
                    $qb->andWhere('p.price >= :priceMin')
                        ->setParameter('priceMin', array_values($criterias->get('price'))[0]);
                }
                if (!empty(array_values($criterias->get('price'))[1])) {
                    $qb->andWhere('p.price <= :priceMax')
                        ->setParameter('priceMax', array_values($criterias->get('price'))[1]);
                }
            }
            if ($criterias->get('area') !== null) {
                if (!empty(array_values($criterias->get('area'))[0])) {
                    $qb->andWhere('p.area >= :areaMin')
                        ->setParameter('areaMin', array_values($criterias->get('area'))[0]);
                }
                if (!empty(array_values($criterias->get('area'))[1])) {
                    $qb->andWhere('p.area <= :areaMax')
                        ->setParameter('areaMax', array_values($criterias->get('area'))[1]);
                }
            }
            if ($criterias->get('rooms') !== null) {
                if (!empty(array_values($criterias->get('rooms'))[0])) {
                    $qb->andWhere('p.rooms >= :roomsMin')
                        ->setParameter('roomsMin', array_values($criterias->get('rooms'))[0]);
                }
                if (!empty(array_values($criterias->get('rooms'))[1])) {
                    $qb->andWhere('p.rooms <= :roomsMax')
                        ->setParameter('roomsMax', array_values($criterias->get('rooms'))[1]);
                }
            }
            if ($criterias->get('energy') !== null) {
                $qb->andWhere('p.energy IN(:energyList)')
                    ->setParameter('energyList', array_values($criterias->get('energy')));
            }
            if ($criterias->get('ges') !== null) {
                $qb->andWhere('p.ges IN(:gesList)')
                    ->setParameter('gesList', array_values($criterias->get('ges')));
            }
            if ($criterias->get('options') !== null) {
                $qb->andWhere('opt.name IN(:optionList)')
                    ->setParameter('optionList', array_values($criterias->get('options')));
                
            }
        }
        if ($sortBy === 'property_id') {
            $qb->orderBy('p.id', $order);
        } else {
            $qb->orderBy('o.' . $sortBy, $order);
        }

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
   
}
