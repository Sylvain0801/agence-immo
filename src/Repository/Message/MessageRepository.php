<?php

namespace App\Repository\Message;

use App\Entity\Message\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findArrayMessageSent(string $sortBy = 'created_at', string $order = 'DESC', int $userId = null) : array
    {
        $qb = $this->createQueryBuilder('m')
                    ->leftJoin('m.recipients', 'rts')
                    ->addSelect('rts')
                    ->leftJoin('rts.recipient', 'r')
                    ->addSelect('r');
                      
        if ($userId !== null) {
            $qb->where('m.sender =:userId')
                ->setParameter('userId', $userId);
        }

        if ($sortBy === 'recipient') {
            $qb->orderBy('r.lastname', $order);
        } else if ($sortBy === 'content') {
            $qb->orderBy('m.subject', $order);
        } else if ($sortBy === 'created_at') {
            $qb->orderBy('m.created_at', $order);
        }

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
}
