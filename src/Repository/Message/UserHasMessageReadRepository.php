<?php

namespace App\Repository\Message;

use App\Entity\Message\UserHasMessageRead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserHasMessageRead|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserHasMessageRead|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserHasMessageRead[]    findAll()
 * @method UserHasMessageRead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserHasMessageReadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserHasMessageRead::class);
    }

    public function findArrayAllDatas(string $sortBy = 'is_read', string $order = 'asc', int $userId = null) : array
    {
        $qb = $this->createQueryBuilder('um')
                ->leftJoin('um.message', 'msg')
                ->addSelect('msg')
                ->leftJoin('msg.sender', 's')
                ->addSelect('s');
                
        if ($userId !== null) {
            $qb->leftJoin('um.recipient', 'r')
                ->addSelect('r')
                ->where('r.id =:userId')
                ->setParameter('userId', $userId);
        }

        if ($sortBy === 'sender') {
            $qb->orderBy('s.lastname', $order);
        } else if ($sortBy === 'content') {
            $qb->orderBy('msg.subject', $order);
        } else if ($sortBy === 'created_at') {
            $qb->orderBy('msg.created_at', $order);
        } else {
            $qb->orderBy('um.'.$sortBy, $order);

        }

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function countMsgNotReadByUser(int $userId = null) : array
    {
        $qb = $this->createQueryBuilder('um');

        if ($userId !== null) {
            $qb->leftJoin('um.recipient', 'r')
                ->addSelect('r')
                ->where('r.id =:userId')
                ->setParameter('userId', $userId);
        }
                
        $qb->andWhere('um.is_read = 0');

        return $qb->getQuery()->getResult();
    }
}
