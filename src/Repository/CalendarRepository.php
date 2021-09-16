<?php

namespace App\Repository;

use App\Entity\Calendar;
use App\Entity\User\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calendar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendar[]    findAll()
 * @method Calendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendar::class);
    }

    public function getAllRemindersForNextTwoMonths(?User $user) {
        $start = new DateTime();
        $end = (new DateTime())->setTimestamp(strtotime("now + 2 months"));
        $qb = $this->createQueryBuilder('c')
                ->where('c.tenant = :user')
                ->setParameter('user', $user)
                ->andWhere('c.start >= :start')
                ->setParameter('start', $start)
                ->andWhere('c.start <= :end')
                ->setParameter('end', $end);

        return $qb->getQuery()->getResult();
    }
}