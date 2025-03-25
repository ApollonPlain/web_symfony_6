<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countCategoriesCreatedInMonth(\DateTime $date): int
    {
        $startOfMonth = clone $date;
        $startOfMonth->setTime(0, 0, 0);

        $endOfMonth = clone $date;
        $endOfMonth->modify('last day of this month');
        $endOfMonth->setTime(23, 59, 59);

        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.createdAt >= :start')
            ->andWhere('c.createdAt <= :end')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getCategoryStatistics(Category $category): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                COUNT(DISTINCT q.id) as total_quizzes,
                COUNT(DISTINCT rm.id) as total_attempts,
                AVG(CASE WHEN rm.is_correct THEN 1 ELSE 0 END) * 100 as success_rate
            FROM category c
            LEFT JOIN quiz q ON q.category_id = c.id
            LEFT JOIN result_mcq rm ON rm.quiz_id = q.id
            WHERE c.id = :categoryId
        ';

        $result = $conn->executeQuery($sql, ['categoryId' => $category->getId()])->fetchAssociative();

        // Format the results
        return [
            'total_quizzes' => (int) $result['total_quizzes'],
            'total_attempts' => (int) $result['total_attempts'],
            'success_rate' => round((float) $result['success_rate'], 2),
        ];
    }

    public function getTopPerformingCategories(int $limit = 5): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                c.id,
                c.name,
                COUNT(DISTINCT rm.id) as attempt_count,
                AVG(CASE WHEN rm.is_correct THEN 1 ELSE 0 END) * 100 as success_rate
            FROM category c
            LEFT JOIN quiz q ON q.category_id = c.id
            LEFT JOIN result_mcq rm ON rm.quiz_id = q.id
            GROUP BY c.id, c.name
            HAVING COUNT(DISTINCT rm.id) > 0
            ORDER BY success_rate DESC
            LIMIT :limit
        ';

        return $conn->executeQuery($sql, ['limit' => $limit])->fetchAllAssociative();
    }

    public function getDailyActivityStats(Category $category, int $days = 7): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                DATE(rm.datetime) as date,
                COUNT(DISTINCT rm.id) as attempts,
                AVG(CASE WHEN rm.is_correct THEN 1 ELSE 0 END) * 100 as daily_success_rate
            FROM category c
            JOIN quiz q ON q.category_id = c.id
            JOIN result_mcq rm ON rm.quiz_id = q.id
            WHERE c.id = :categoryId
            AND rm.datetime >= CURRENT_DATE - :days * INTERVAL \'1 day\'
            GROUP BY DATE(rm.datetime)
            ORDER BY date DESC
        ';

        return $conn->executeQuery($sql, [
            'categoryId' => $category->getId(),
            'days' => $days,
        ])->fetchAllAssociative();
    }
}
