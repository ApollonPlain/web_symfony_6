<?php

namespace App\Service;

use App\Entity\Quiz;
use App\Repository\QuizRepository;

class QuizService
{
    public function __construct(private QuizRepository $quizRepository)
    {
    }

    public function getRandomizedAnswers(Quiz $quiz): array
    {
        $quizAnswers[] = [
            'answer' => $quiz->getAnswerA(),
            'id' => 'A',
        ];

        if (null !== $quiz->getAnswerB()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerB(),
                'id' => 'B',
            ];
        }

        if (null !== $quiz->getAnswerC()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerC(),
                'id' => 'C',
            ];
        }

        if (null !== $quiz->getAnswerD()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerD(),
                'id' => 'D',
            ];
        }

        if (null !== $quiz->getAnswerE()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerE(),
                'id' => 'E',
            ];
        }

        if (null !== $quiz->getAnswerF()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerF(),
                'id' => 'F',
            ];
        }

        if (null !== $quiz->getAnswerG()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerG(),
                'id' => 'G',
            ];
        }

        if (null !== $quiz->getAnswerH()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerH(),
                'id' => 'H',
            ];
        }

        shuffle($quizAnswers);

        return $quizAnswers;
    }

    public function isMQCGood(Quiz $quiz, array $quizSent): bool
    {
        if ((isset($quizSent['A']) && !$quiz->isIsA()) || (!isset($quizSent['A']) && $quiz->isIsA())) {
            return false;
        }

        if ((isset($quizSent['B']) && !$quiz->isIsB()) || (!isset($quizSent['B']) && $quiz->isIsB())) {
            return false;
        }

        if ((isset($quizSent['C']) && !$quiz->isIsC()) || (!isset($quizSent['C']) && $quiz->isIsC())) {
            return false;
        }

        if ((isset($quizSent['D']) && !$quiz->isIsD()) || (!isset($quizSent['D']) && $quiz->isIsD())) {
            return false;
        }

        if ((isset($quizSent['E']) && !$quiz->isIsE()) || (!isset($quizSent['E']) && $quiz->isIsE())) {
            return false;
        }

        if ((isset($quizSent['F']) && !$quiz->isIsF()) || (!isset($quizSent['F']) && $quiz->isIsF())) {
            return false;
        }

        if ((isset($quizSent['G']) && !$quiz->isIsG()) || (!isset($quizSent['G']) && $quiz->isIsG())) {
            return false;
        }

        if ((isset($quizSent['H']) && !$quiz->isIsH()) || (!isset($quizSent['H']) && $quiz->isIsH())) {
            return false;
        }

        return true;
    }

    public function getRightsAnswers(Quiz $quiz): array
    {
        if ($quiz->isIsA()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerA(),
                'id' => 'A',
            ];
        }

        if ($quiz->isIsB()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerB(),
                'id' => 'B',
            ];
        }

        if ($quiz->isIsC()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerC(),
                'id' => 'C',
            ];
        }

        if ($quiz->isIsD()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerD(),
                'id' => 'D',
            ];
        }

        if ($quiz->isIsE()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerE(),
                'id' => 'E',
            ];
        }

        if ($quiz->isIsF()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerF(),
                'id' => 'F',
            ];
        }

        if ($quiz->isIsG()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerG(),
                'id' => 'G',
            ];
        }

        if ($quiz->isIsH()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerH(),
                'id' => 'H',
            ];
        }

        return $quizAnswers ?? [];
    }

    public function getQuizzes(int $max = 0)
    {
        $quizzes = $this->quizRepository->getQuizzesWithMaxCorrectAnswers($max);

        if (empty($quizzes)) {
            throw new \RuntimeException('No quiz found');
        }

        // Convert the results to Quiz objects only
        return array_map(function ($result) {
            return $result[0];
        }, $quizzes);
    }

    public function getQuizExam(int $size = 10, int $max = 0): array
    {
        $quizzes = $this->getQuizzes($max);

        shuffle($quizzes);

        return array_slice($quizzes, 0, $size);
    }
}
