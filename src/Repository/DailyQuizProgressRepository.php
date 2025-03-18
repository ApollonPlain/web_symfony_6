<?php

namespace App\Repository;

use App\Entity\DailyQuizProgress;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DailyQuizProgress>
 *
 * @method DailyQuizProgress|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyQuizProgress|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyQuizProgress[]    findAll()
 * @method DailyQuizProgress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyQuizProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyQuizProgress::class);
    }

    public function save(DailyQuizProgress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DailyQuizProgress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTodayProgressForUser(User $user): ?DailyQuizProgress
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        return $this->createQueryBuilder('dp')
            ->andWhere('dp.user = :user')
            ->andWhere('dp.date = :today')
            ->setParameter('user', $user)
            ->setParameter('today', $today)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
