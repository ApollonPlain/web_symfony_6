<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use App\Repository\QuizSessionRepository;
use App\Repository\ResultMCQRepository;
use App\Service\CheckService;
use App\Service\QuizService;
use App\Service\ResultMCQService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    public function __construct(
    ) {
    }

    #[Cache(smaxage: 60, public: true)]
    #[Route('/', name: 'app_quiz_index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository, ResultMCQRepository $resultMCQRepository): Response
    {
        $results = $resultMCQRepository->findAll();
        $resultsTrue = $resultMCQRepository->findBy(['isCorrect' => true]);
        $resultsFalse = $resultMCQRepository->findBy(['isCorrect' => false]);

        $quizzes = $quizRepository->findBy([], ['id' => 'DESC']);

        $dateTime = new \DateTime();

        return $this->render('quiz/index.html.twig', [
            'time' => $dateTime->format('Y-m-d H:i:s'),
            'quizzes' => $quizzes,
            'count_results' => count($results),
            'count_results_true' => count($resultsTrue),
            'count_results_false' => count($resultsFalse),
        ]);
    }

    #[Route('/new', name: 'app_quiz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuizRepository $quizRepository): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizRepository->save($quiz, true);

            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_quiz_show', methods: ['GET'])]
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[Route('/q/{id}/edit', name: 'app_quiz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, QuizRepository $quizRepository): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizRepository->save($quiz, true);

            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_quiz_delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, QuizRepository $quizRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
            $quizRepository->remove($quiz, true);
        }

        return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mcq/{max}', name: 'app_quiz_mcq', methods: ['GET', 'POST'])]
    public function mcq(
        QuizRepository $quizRepository,
        Request $request,
        QuizService $quizService,
        ResultMCQService $resultMCQService,
        CheckService $checkService,
        QuizSessionRepository $quizSessionRepository,
        EntityManagerInterface $entityManager,
        int $max = 0,
    ): Response {
        try {
            $categoryId = $request->query->get('category');
            $dailyProgress = $resultMCQService->getTodayProgressForCurrentUser();
            $session = null;

            // Check if we're in a quiz session
            if ($this->getUser()) {
                $session = $quizSessionRepository->findActiveSessionForUser($this->getUser());

                // If we have an active session and we're not in an AJAX request,
                // redirect to the session play route
                if ($session && !$request->isXmlHttpRequest()) {
                    return $this->redirectToRoute('app_quiz_session_play', ['id' => $session->getId()]);
                }
            }

            if ($request->isMethod(Request::METHOD_POST)) {
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

                // Handle session mode
                if ($session && $request->isXmlHttpRequest()) {
                    if (!$session->isCompleted()) {
                        if (!$goodResponse) {
                            $session->loseLife();
                            if (0 === $session->getCurrentLives()) {
                                $session->setIsCompleted(true);
                                $session->setIsWon(false);
                            }
                        } else {
                            $session->incrementStreak();
                        }

                        $entityManager->flush();

                        return new JsonResponse([
                            'correct' => $goodResponse,
                            'currentLives' => $session->getCurrentLives(),
                            'currentStreak' => $session->getCurrentStreak(),
                            'bestStreak' => $session->getBestStreak(),
                            'gameOver' => $session->isCompleted(),
                            'message' => $goodResponse ? 'Correct answer!' : 'Incorrect answer!',
                        ]);
                    } else {
                        return new JsonResponse([
                            'error' => 'Session is already completed',
                            'gameOver' => true,
                        ], Response::HTTP_BAD_REQUEST);
                    }
                }

                $resultMCQService->saveResultMCQ($quiz, $goodResponse);
                $dailyProgress = $resultMCQService->getTodayProgressForCurrentUser();

                return $this->render('quiz/quiz.html.twig', [
                    'quiz' => $quiz,
                    'answers' => $quizService->getRightsAnswers($quiz),
                    'goodResponse' => $goodResponse,
                    'max' => $max,
                    'check' => $checkService->isCheckQuiz(),
                    'category' => $categoryId,
                    'dailyProgress' => $dailyProgress,
                ]);
            }

            $quizzes = $quizService->getQuizzes($max, $categoryId);
            if (empty($quizzes)) {
                throw new \RuntimeException('No quizzes available');
            }

            shuffle($quizzes);
            $quiz = $quizzes[0];
            $quizAnswersRandomised = $quizService->getRandomizedAnswers($quiz);

            return $this->render('quiz/quiz.html.twig', [
                'quiz' => $quiz,
                'answers' => $quizAnswersRandomised,
                'max' => $max,
                'category' => $categoryId,
                'dailyProgress' => $dailyProgress,
            ]);
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'error' => $e->getMessage(),
                ], Response::HTTP_BAD_REQUEST);
            }

            throw $e;
        }
    }

    #[Route('/exam/{max}/{number}', name: 'app_quiz_exam', methods: ['GET', 'POST'])]
    public function exam(QuizRepository $quizRepository, Request $request, QuizService $quizService, ResultMCQService $resultMCQService, CheckService $checkService, int $max = 0, int $number = 10): Response
    {
        $dailyProgress = $resultMCQService->getTodayProgressForCurrentUser();

        if ($request->isMethod(Request::METHOD_POST)) {
            $quizSent = $request->request->all();

            $i = 0;
            $quizzes = [];

            foreach ($quizSent as $key => $value) {
                $keyExploded = explode('-', $key);

                if ('id' === $keyExploded[1]) {
                    $idQuiz = $value;

                    $quizzes[$idQuiz] = [];
                } else {
                    if (!isset($idQuiz)) {
                        dd('Error id not set');
                    }
                    $quizzes[$idQuiz][$keyExploded[1]] = $value;
                }
            }

            $goodResponse = [];
            $answers = [];
            $checks = [];

            foreach ($quizzes as $id => $quizAnswers) {
                $quiz = $quizRepository->findOneBy(['id' => $id]);
                $goodResponse[$id] = $quizService->isMQCGood($quiz, $quizAnswers);
                $resultMCQService->saveResultMCQ($quiz, $goodResponse[$id]);
                $answers[$id] = $quizService->getRightsAnswers($quiz);
                $quizzes[$id] = $quiz;
                $checks[$id] = $checkService->isCheckQuiz();
            }

            // Refresh daily progress after saving results
            $dailyProgress = $resultMCQService->getTodayProgressForCurrentUser();

            return $this->render('quiz/exam.html.twig', [
                'quizzes' => $quizzes,
                'quizzesAnswers' => $answers,
                'goodResponse' => $goodResponse,
                'nbGoodResponse' => count(array_filter($goodResponse)),
                'max' => $max,
                'number' => $number,
                'checks' => $checks,
                'dailyProgress' => $dailyProgress,
            ]);
        }

        $quizzes = $quizService->getQuizExam($number, $max);
        shuffle($quizzes);

        $quizzesAnswers = [];
        foreach ($quizzes as $quiz) {
            $quizzesAnswers[] = $quizService->getRandomizedAnswers($quiz);
        }

        return $this->render('quiz/exam.html.twig', [
            'quizzes' => $quizzes,
            'quizzesAnswers' => $quizzesAnswers,
            'number' => $number,
            'max' => $max,
            'dailyProgress' => $dailyProgress,
        ]);
    }
}
