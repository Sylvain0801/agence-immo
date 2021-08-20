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

    public function getListOwnersSorted(): array
    {
        $qb = $this->createQueryBuilder('o')
                ->distinct()
                ->orderBy('o.lastname', 'asc');
        
        return $qb->getQuery()->getResult();
    }
}
