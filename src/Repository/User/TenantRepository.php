<?php

namespace App\Repository\User;

use App\Entity\User\Tenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tenant[]    findAll()
 * @method Tenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tenant::class);
    }

    public function findArrayAllDatas(string $sortBy = 'lastname', string $order = 'asc', int $userId = null) : array
    {
        $qb = $this->createQueryBuilder('t')
                ->leftJoin('t.tenant_property', 'p')
                ->addSelect('p')
                ->orderBy('t.' . $sortBy, $order);
        
        if ($userId !== null) {
            $qb->leftJoin('p.manager_property', 'm')
                ->addSelect('m')
                ->where('m.id =:userId')
                ->setParameter('userId', $userId);
        }

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
}
