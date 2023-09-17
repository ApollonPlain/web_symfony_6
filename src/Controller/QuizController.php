<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use App\Repository\ResultMCQRepository;
use App\Service\CheckService;
use App\Service\QuizService;
use App\Service\ResultMCQService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    #[Route('/', name: 'app_quiz_index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository, ResultMCQRepository $resultMCQRepository): Response
    {
        $results = $resultMCQRepository->findAll();
        $resultsTrue = $resultMCQRepository->findBy(['isCorrect' => true]);
        $resultsFalse = $resultMCQRepository->findBy(['isCorrect' => false]);

        $quizzes = $quizRepository->findAll();

        return $this->render('quiz/index.html.twig', [
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
    public function mcq(QuizRepository $quizRepository, Request $request, QuizService $quizService, ResultMCQService $resultMCQService, CheckService $checkService, int $max = 0): Response
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $quizSent = $request->request->all();

            $quiz = $quizRepository->findOneBy(['id' => $quizSent['id']]);

            $goodResponse = $quizService->isMQCGood($quiz, $quizSent);
            $resultMCQService->saveResultMCQ($quiz, $goodResponse);

            return $this->render('quiz/quiz.html.twig', [
                'quiz' => $quiz,
                'answers' => $quizService->getRightsAnswers($quiz),
                'goodResponse' => $goodResponse,
                'max' => $max,
                'check' => $checkService->isCheckQuiz(),
            ]);
        }

        $quizzes = $quizService->getQuizzes($max);

        shuffle($quizzes);
        $quiz = $quizzes[0];

        $quizAnswersRandomised = $quizService->getRandomizedAnswers($quiz);

        return $this->render('quiz/quiz.html.twig', [
            'quiz' => $quiz,
            'answers' => $quizAnswersRandomised,
            'max' => $max,
        ]);
    }

    #[Route('/exam/{max}/{number}', name: 'app_quiz_exam', methods: ['GET', 'POST'])]
    public function exam(QuizRepository $quizRepository, Request $request, QuizService $quizService, ResultMCQService $resultMCQService, CheckService $checkService, int $max = 0, int $number = 10): Response
    {
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

            return $this->render('quiz/exam.html.twig', [
                'quizzes' => $quizzes,
                'quizzesAnswers' => $answers,
                'goodResponse' => $goodResponse,
                'max' => $max,
                'number' => $number,
                'checks' => $checks,
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
        ]);
    }
}
