<?php

namespace App\Repository;

use App\Entity\Document;
use App\Entity\User\User;
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

    public function findArrayAllDatas(string $order = 'asc', ?User $user) : array
    {
        $documents = [];
        foreach ($user->getDocuments() as $document) {
            $documents[] = $document->getId();
        }
        $qb = $this->createQueryBuilder('d')
                ->join('d.users', 'u')
                ->addSelect('u')
                ->orderBy('d.title', $order);
        if ($user) {
            $qb->andWhere('d.id IN(:docList)')
                ->setParameter('docList', array_values($documents));
        }

        return $qb->getQuery()->getResult();
    }

    public function findDocbyIdAndByUserId(int $docId, ?User $user) : array
    {
        $qb = $this->createQueryBuilder('d')
                ->leftJoin('d.users', 'u')
                ->addSelect('u')
                ->where('d.id = :docId')
                ->setParameter('docId', $docId)
                ->andWhere('u.id = :userId')
                ->setParameter('userId', $user->getId());
        
        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
}
