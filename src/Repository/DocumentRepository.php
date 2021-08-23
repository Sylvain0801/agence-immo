<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function findArrayAllDatas(string $order = 'asc') : array
    {
        $qb = $this->createQueryBuilder('d')
                ->addSelect('d')
                ->leftJoin('d.users', 'u')
                ->addSelect('u')
                ->orderBy('d.title', $order);

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
}
