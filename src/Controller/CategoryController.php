<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\QuizRepository;
use App\Repository\ResultMCQRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/statistics', name: 'app_category_statistics')]
    public function statistics(CategoryRepository $categoryRepository, ResultMCQRepository $resultMCQRepository, QuizRepository $quizRepository): Response
    {
        $categories = $categoryRepository->findAll();

        // Basic statistics
        $totalCategories = count($categories);
        $activeCategories = count(array_filter($categories, fn ($cat) => $cat->isActive()));

        // Get historical data (last 6 months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; --$i) {
            $date = new \DateTime("first day of -$i month");
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'count' => $categoryRepository->countCategoriesCreatedInMonth($date),
            ];
        }

        // Get monthly answer statistics (last 6 months)
        $monthlyAnswerStats = [];
        for ($i = 5; $i >= 0; --$i) {
            $date = new \DateTime("first day of -$i month");
            $monthlyAnswerStats[] = [
                'month' => $date->format('M Y'),
                'statistics' => $resultMCQRepository->countAnswersByMonth($date),
            ];
        }

        // Get weekly answer statistics (last 12 weeks)
        $weeklyAnswerStats = [];
        for ($i = 11; $i >= 0; --$i) {
            $date = new \DateTime();
            $date->modify("-$i week");
            $date->modify('monday this week'); // Start each week on Monday
            $weeklyAnswerStats[] = [
                'week_number' => $date->format('W'),
                'statistics' => $resultMCQRepository->countAnswersByWeek($date),
            ];
        }

        // Get daily answer statistics (last 15 days)
        $dailyAnswerStats = [];
        for ($i = 14; $i >= 0; --$i) {
            $date = new \DateTime();
            $date->modify("-$i days");
            $dailyAnswerStats[] = $resultMCQRepository->countAnswersByDay($date);
        }

        // Get detailed statistics for each category
        $categoryStats = [];
        foreach ($categories as $category) {
            $categoryStats[$category->getId()] = [
                'name' => $category->getName(),
                'stats' => $categoryRepository->getCategoryStatistics($category),
                'daily_activity' => $categoryRepository->getDailyActivityStats($category),
            ];
        }

        // Get top performing categories
        $topCategories = $categoryRepository->getTopPerformingCategories(50);

        $completion1 = $resultMCQRepository->countHowGoodAnswersByQuiz();
        $completion2 = $resultMCQRepository->countHowGoodAnswersByQuiz(2);
        $completion3 = $resultMCQRepository->countHowGoodAnswersByQuiz(3);

        $completion_rate_1 = round($completion1 / $quizRepository->count([]) * 100, 1);
        $completion_rate_2 = round($completion2 / $quizRepository->count([]) * 100, 1);
        $completion_rate_3 = round($completion3 / $quizRepository->count([]) * 100, 1);

        return $this->render('category/statistics.html.twig', [
            'total_categories' => $totalCategories,
            'active_categories' => $activeCategories,
            'monthly_stats' => $monthlyStats,
            'monthly_answer_stats' => $monthlyAnswerStats,
            'weekly_answer_stats' => $weeklyAnswerStats,
            'daily_answer_stats' => $dailyAnswerStats,
            'category_stats' => $categoryStats,
            'top_categories' => $topCategories,
            'completion_rate_1' => $completion_rate_1,
            'completion_rate_2' => $completion_rate_2,
            'completion_rate_3' => $completion_rate_3,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
