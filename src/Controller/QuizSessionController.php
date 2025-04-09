<?php

namespace App\Controller;

use App\Entity\QuizSession;
use App\Repository\QuizRepository;
use App\Repository\QuizSessionRepository;
use App\Service\CheckService;
use App\Service\QuizService;
use App\Service\ResultMCQService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quiz/session')]
class QuizSessionController extends AbstractController
{
    #[Route('/start', name: 'app_quiz_session_start', methods: ['GET'])]
    public function start(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Check if user has an active session
        $activeSession = $entityManager->getRepository(QuizSession::class)->findActiveSessionForUser($this->getUser());

        if ($activeSession) {
            // Redirect to the active session
            return $this->redirectToRoute('app_quiz_session_play', ['id' => $activeSession->getId()]);
        }

        return $this->render('quiz/session/start.html.twig', [
            'difficulties' => [
                'very_easy' => [
                    'name' => 'Très Facile',
                    'lives' => QuizSession::LIVES_BY_DIFFICULTY[QuizSession::DIFFICULTY_VERY_EASY],
                    'value' => QuizSession::DIFFICULTY_VERY_EASY,
                    'description' => '5 vies pour commencer, parfait pour s\'entraîner',
                ],
                'easy' => [
                    'name' => 'Facile',
                    'lives' => QuizSession::LIVES_BY_DIFFICULTY[QuizSession::DIFFICULTY_EASY],
                    'value' => QuizSession::DIFFICULTY_EASY,
                    'description' => '4 vies pour commencer, parfait pour s\'entraîner',
                ],
                'medium' => [
                    'name' => 'Normal',
                    'lives' => QuizSession::LIVES_BY_DIFFICULTY[QuizSession::DIFFICULTY_MEDIUM],
                    'value' => QuizSession::DIFFICULTY_MEDIUM,
                    'description' => '3 vies pour commencer, un bon défi',
                ],
                'hard' => [
                    'name' => 'Difficile',
                    'lives' => QuizSession::LIVES_BY_DIFFICULTY[QuizSession::DIFFICULTY_HARD],
                    'value' => QuizSession::DIFFICULTY_HARD,
                    'description' => '2 vies pour commencer, pour les experts',
                ],
            ],
        ]);
    }

    #[Route('/create', name: 'app_quiz_session_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $difficulty = $request->request->get('difficulty', QuizSession::DIFFICULTY_MEDIUM);

        // Create new session
        $session = QuizSession::create($this->getUser(), $difficulty);

        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('app_quiz_session_play', ['id' => $session->getId()]);
    }

    #[Route('/{id}/play', name: 'app_quiz_session_play', methods: ['GET', 'POST'])]
    public function play(
        QuizSession $session,
        QuizService $quizService,
        ResultMCQService $resultMCQService,
        CheckService $checkService,
        Request $request,
        QuizRepository $quizRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Ensure the session belongs to the current user
        if ($session->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot access this session.');
        }

        if ($session->isCompleted()) {
            return $this->redirectToRoute('app_quiz_session_result', ['id' => $session->getId()]);
        }

        // Handle POST request (answer submission)
        if ($request->isMethod('POST')) {
            $quizSent = $request->request->all();

            // Validate quiz ID
            if (!isset($quizSent['id'])) {
                throw new \InvalidArgumentException('Quiz ID is required');
            }

            $quiz = $quizRepository->findOneBy(['id' => $quizSent['id']]);
            if (!$quiz) {
                throw new \InvalidArgumentException('Quiz not found');
            }

            $goodResponse = $quizService->isMQCGood($quiz, $quizSent);
            $displayResult = false;

            // Update session state
            if (!$goodResponse) {
                $session->loseLife();
                if (0 === $session->getCurrentLives()) {
                    $session->setIsCompleted(true);
                    $session->setIsWon(false);
                    $displayResult = true;
                }
            } else {
                $session->incrementStreak();
            }

            $entityManager->flush();

            // Save the result
            $resultMCQService->saveResultMCQ($quiz, $goodResponse);

            // If it's an AJAX request, return JSON response
            //            if ($request->isXmlHttpRequest()) {
            //                return new JsonResponse([
            //                    'correct' => $goodResponse,
            //                    'currentLives' => $session->getCurrentLives(),
            //                    'currentStreak' => $session->getCurrentStreak(),
            //                    'bestStreak' => $session->getBestStreak(),
            //                    'gameOver' => $session->isCompleted(),
            //                    'message' => $goodResponse ? 'Correct answer!' : 'Incorrect answer!'
            //                ]);
            //            }

            // For non-AJAX requests, show the result page
            return $this->render('quiz/session/play.html.twig', [
                'session' => $session,
                'quiz' => $quiz,
                'answers' => $quizService->getRightsAnswers($quiz),
                'goodResponse' => $goodResponse,
                'max' => $request->query->get('max', 0),
                'category' => $request->query->get('category'),
                'dailyProgress' => $resultMCQService->getTodayProgressForCurrentUser(),
                'check' => $checkService->isCheckQuiz(),
                'displayResult' => $displayResult,
            ]);
        }

        // Handle GET request (show new question)
        $dailyProgress = $resultMCQService->getTodayProgressForCurrentUser();
        $categoryId = $request->query->get('category');

        $random = mt_rand(1, 100);
        $max = match (true) {
            $random <= 20 => 1,
            $random <= 35 => 2,
            $random <= 50 => 3,
            default => mt_rand(4, 30),
        };

        $quizzes = $quizService->getQuizzes($max, $categoryId);
        if (empty($quizzes)) {
            throw new \RuntimeException('No quizzes available');
        }

        shuffle($quizzes);
        $quiz = $quizzes[0];
        $quizAnswersRandomised = $quizService->getRandomizedAnswers($quiz);

        return $this->render('quiz/session/play.html.twig', [
            'session' => $session,
            'quiz' => $quiz,
            'answers' => $quizAnswersRandomised,
            'max' => $max,
            'category' => $categoryId,
            'dailyProgress' => $dailyProgress,
            'check' => $checkService->isCheckQuiz(),
        ]);
    }

    #[Route('/{id}/result', name: 'app_quiz_session_result', methods: ['GET'])]
    public function result(QuizSession $session, QuizSessionRepository $quizSessionRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Ensure the session belongs to the current user
        if ($session->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot access this session.');
        }

        if (!$session->isCompleted()) {
            return $this->redirectToRoute('app_quiz_session_play', ['id' => $session->getId()]);
        }

        return $this->render('quiz/session/result.html.twig', [
            'session' => $session,
            'best_very_easy' => $quizSessionRepository->findBestCumulateStreakForUser($this->getUser()->getId(), QuizSession::DIFFICULTY_VERY_EASY),
            'best_easy' => $quizSessionRepository->findBestCumulateStreakForUser($this->getUser()->getId(), QuizSession::DIFFICULTY_EASY),
            'best_medium' => $quizSessionRepository->findBestCumulateStreakForUser($this->getUser()->getId(), QuizSession::DIFFICULTY_MEDIUM),
            'best_hard' => $quizSessionRepository->findBestCumulateStreakForUser($this->getUser()->getId(), QuizSession::DIFFICULTY_HARD),
        ]);
    }
}
