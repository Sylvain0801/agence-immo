<?php

namespace App\Repository\Document;

use App\Entity\Document\DocHasSeen;
use App\Entity\Document\Document;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocHasSeen|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocHasSeen|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocHasSeen[]    findAll()
 * @method DocHasSeen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocHasSeenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocHasSeen::class);
    }

    public function findArrayAllDatas(string $sortBy = 'title', string $order = 'asc', ?User $user) : array
    {
        $listDocs = $user->getDocuments();
        $documents = [];
        foreach ($listDocs as $key => $doc) {
            $documents[$key] = $doc->getId();
        }

        $qb = $this->createQueryBuilder('dhs')
                ->leftJoin('dhs.user', 'u')
                ->addSelect('u')
                ->leftJoin('dhs.document', 'd')
                ->addSelect('d');
                
        if ($sortBy === 'new') {
            $qb->orderBy('dhs.has_seen', $order);
        } else {
            $qb->orderBy('d.' . $sortBy, $order);
        }
                

        if ($user) {
            $qb->andWhere('dhs.id IN (:listDocId)')
                ->setParameter('listDocId', array_values($documents));
        }

        return $qb->getQuery()->getResult();
    }
}
