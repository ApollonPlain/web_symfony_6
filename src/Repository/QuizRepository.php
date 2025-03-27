<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getNumberLength(): array
    {
        return $this->createQueryBuilder('q')->where('length(q.question) = 3 and q.category is null')->getQuery()->getResult();
    }

    public function save(Quiz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Quiz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getQuizzesWithMaxCorrectAnswers(?int $max = null, ?int $categoryId = null): array
    {
        $qb = $this->createQueryBuilder('q')
            ->leftJoin('q.resultMCQs', 'r')
            ->addSelect('SUM(CASE WHEN r.isCorrect = true THEN 1 ELSE 0 END) as correctAnswers')
            ->groupBy('q.id');

        if (null !== $max && $max > 0) {
            $qb->having('SUM(CASE WHEN r.isCorrect = true THEN 1 ELSE 0 END) < :max')
               ->setParameter('max', $max);
        }

        if (null !== $categoryId) {
            $qb->andWhere('q.category = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Quiz[] Returns an array of Quiz objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Quiz
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
