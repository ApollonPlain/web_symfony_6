<?php

namespace App\Repository;

use App\Entity\QuizSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizSession>
 *
 * @method QuizSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizSession[]    findAll()
 * @method QuizSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizSession::class);
    }

    public function findActiveSessionForUser($userId): ?QuizSession
    {
        return $this->createQueryBuilder('qs')
            ->andWhere('qs.user = :userId')
            ->andWhere('qs.isCompleted = :isCompleted')
            ->setParameter('userId', $userId)
            ->setParameter('isCompleted', false)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findCompletedSessionsForUser($userId, $limit = 10): array
    {
        return $this->createQueryBuilder('qs')
            ->andWhere('qs.user = :userId')
            ->andWhere('qs.isCompleted = :isCompleted')
            ->setParameter('userId', $userId)
            ->setParameter('isCompleted', true)
            ->orderBy('qs.completedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
