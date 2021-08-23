<?php

namespace App\Repository\User;

use App\Entity\User\Owner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Owner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Owner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Owner[]    findAll()
 * @method Owner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OwnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Owner::class);
    }

    public function findArrayAllDatas(string $sortBy = 'lastname', string $order = 'asc', int $userId = null) : array
    {
        $qb = $this->createQueryBuilder('o')
                ->leftJoin('o.owner_properties', 'p')
                ->addSelect('p')
                ->orderBy('o.' . $sortBy, $order);
        
        if ($userId !== null) {
            $qb->leftJoin('p.manager_property', 'm')
                ->addSelect('m')
                ->where('m.id =:userId')
                ->setParameter('userId', $userId);
        }

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getListOwnersSorted(): array
    {
        $qb = $this->createQueryBuilder('o')
                ->distinct()
                ->orderBy('o.lastname', 'asc');
        
        return $qb->getQuery()->getResult();
    }

    public function findOwnerInformations(int $id)
    {
        $qb = $this->createQueryBuilder('o')
                ->where('o.id =:id')
                ->setParameter('id', $id);
                

        return $qb->getQuery()->getResult();
    }
}
