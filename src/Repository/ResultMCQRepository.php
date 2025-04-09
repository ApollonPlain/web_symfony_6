<?php

namespace App\Repository;

use App\Entity\ResultMCQ;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResultMCQ>
 *
 * @method ResultMCQ|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultMCQ|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultMCQ[]    findAll()
 * @method ResultMCQ[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultMCQRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultMCQ::class);
    }

    public function save(ResultMCQ $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ResultMCQ $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countHowGoodAnswersByQuiz(int $nbGoodAnswers = 1): int
    {
        $subquery = $this->createQueryBuilder('r')
            ->select('COUNT(r.id) as correct_count')
            ->where('r.isCorrect = true')
            ->groupBy('r.quiz')
            ->having('COUNT(r.id) >= :nbGoodAnswers')
            ->setParameter('nbGoodAnswers', $nbGoodAnswers)
            ->getQuery()
            ->getResult();

        return count($subquery);
    }

    public function countAnswersByMonth(\DateTime $date): array
    {
        $startOfMonth = clone $date;
        $startOfMonth->setTime(0, 0, 0);

        $endOfMonth = clone $date;
        $endOfMonth->modify('last day of this month');
        $endOfMonth->setTime(23, 59, 59);

        $totalCount = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.datetime >= :start')
            ->andWhere('r.datetime <= :end')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->getQuery()
            ->getSingleScalarResult();

        $correctCount = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.datetime >= :start')
            ->andWhere('r.datetime <= :end')
            ->andWhere('r.isCorrect = true')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total' => (int) $totalCount,
            'correct' => (int) $correctCount,
            'success_rate' => $totalCount > 0 ? round(($correctCount / $totalCount) * 100, 1) : 0,
        ];
    }

    public function countAnswersByWeek(\DateTime $weekStart): array
    {
        $startOfWeek = clone $weekStart;
        $startOfWeek->setTime(0, 0, 0);

        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');
        $endOfWeek->setTime(23, 59, 59);

        $totalCount = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.datetime >= :start')
            ->andWhere('r.datetime <= :end')
            ->setParameter('start', $startOfWeek)
            ->setParameter('end', $endOfWeek)
            ->getQuery()
            ->getSingleScalarResult();

        $correctCount = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.datetime >= :start')
            ->andWhere('r.datetime <= :end')
            ->andWhere('r.isCorrect = true')
            ->setParameter('start', $startOfWeek)
            ->setParameter('end', $endOfWeek)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'week_range' => $startOfWeek->format('M d').' - '.$endOfWeek->format('M d'),
            'total' => (int) $totalCount,
            'correct' => (int) $correctCount,
            'success_rate' => $totalCount > 0 ? round(($correctCount / $totalCount) * 100, 1) : 0,
        ];
    }

    //    /**
    //     * @return ResultMCQ[] Returns an array of ResultMCQ objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ResultMCQ
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
