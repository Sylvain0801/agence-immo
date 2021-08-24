<?php

namespace App\Repository\Property;

use App\Entity\Property\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }
    
    public function findArrayAllDatas(int $userId = null) : array
    {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('p')
                ->leftJoin('p.city', 'c')
                ->addSelect('c')
                ->leftJoin('p.property_type', 'pp')
                ->addSelect('pp')
                ->leftJoin('p.owner_property', 'ow')
                ->addSelect('ow')
                ->leftJoin('p.property_tenants', 'te')
                ->addSelect('te')
                ->leftJoin('p.options', 'o')
                ->addSelect('o')
                ->leftJoin('p.offers', 'po')
                ->addSelect('po');

        if ($userId !== null) {
            $qb->leftJoin('p.manager_property', 'm')
                ->addSelect('m')
                ->where('m.id =:userId')
                ->setParameter('userId', $userId);
        }

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function findListByFieldSortAscending(string $field = null) : array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.' . $field)
            ->distinct()
            ->orderBy('p.' . $field, 'asc');

        return $qb->getQuery()->execute();
    }

    public function findListCitiesByCriteria() : array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.city', 'c')
            ->select('c.name AS city')
            ->distinct()
            ->orderBy('city', 'asc');

        return $qb->getQuery()->execute();
    }

    public function findListTypesByCriteria() : array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.property_type', 'pt')
            ->select('pt.name AS property_type')
            ->distinct()
            ->orderBy('property_type', 'asc');

        return $qb->getQuery()->execute();
    }

    public function findListOptions() : array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.options', 'o')
            ->select('o.name AS options')
            ->distinct()
            ->orderBy('options', 'asc');

        return $qb->getQuery()->execute();
    }

    public function findListOwners() : array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.owner_property', 'o')
            ->select('CONCAT(o.lastname, \' \', o.firstname) AS owner_property')
            ->distinct()
            ->orderBy('owner_property', 'asc');

        return $qb->getQuery()->execute();
    }

    public function findListSortedFilteredBycriteria($criterias = null, string $sortBy = 'id', string $order = 'asc', int $userId = null) : array
    {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('p')
                ->leftJoin('p.city', 'c')
                ->addSelect('c')
                ->leftJoin('p.property_type', 't')
                ->addSelect('t')
                ->leftJoin('p.owner_property', 'ow')
                ->addSelect('ow')
                ->leftJoin('p.property_tenants', 'te')
                ->addSelect('te')
                ->leftJoin('p.options', 'o')
                ->addSelect('o')
                ->leftJoin('p.offers', 'po')
                ->addSelect('po');
                
        if ($userId !== null) {
            $qb->leftJoin('p.manager_property', 'm')
                ->addSelect('m')
                ->where('m.id =:userId')
                ->setParameter('userId', $userId);
        }

        if ($criterias !== null) {
            if ($criterias->get('id') !== null) {
                $qb->andWhere('p.id IN(:idList)')
                    ->setParameter('idList', array_values($criterias->get('id')));
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
                $qb->andWhere('o.name IN(:optionList)')
                    ->setParameter('optionList', array_values($criterias->get('options')));
                
            }
        }
        if ($sortBy === 'city') {
            $qb->orderBy('c.name', $order);
        } else if ($sortBy === 'property_type') {
            $qb->orderBy('t.name', $order);
        } else if ($sortBy === 'owner_property') {
            $qb->orderBy('ow.lastname', $order);
        } else {
            $qb->orderBy('p.' . $sortBy, $order);
        }

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

}
