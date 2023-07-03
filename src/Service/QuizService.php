<?php

namespace App\Service;

use App\Entity\Quiz;

class QuizService
{
    public function getRandomizedAnswers(Quiz$quiz): array
    {
        $quizAnswers[] = [
            'answer' => $quiz->getAnswerA(),
            'id' => 'A'
        ];

        if ($answerB = $quiz->getAnswerB()) {
            $quizAnswers[] = [
                'answer' => $answerB,
                'id' => 'B'
            ];
        }

        if ($answerC = $quiz->getAnswerC()) {
            $quizAnswers[] = [
                'answer' => $answerC,
                'id' => 'C'
            ];
        }

        if ($answerD = $quiz->getAnswerD()) {
            $quizAnswers[] = [
                'answer' => $answerD,
                'id' => 'D'
            ];
        }

        if ($answerE = $quiz->getAnswerE()) {
            $quizAnswers[] = [
                'answer' => $answerE,
                'id' => 'E'
            ];
        }

        if ($answerF = $quiz->getAnswerF()) {
            $quizAnswers[] = [
                'answer' => $answerF,
                'id' => 'F'
            ];
        }

        if ($answerG = $quiz->getAnswerG()) {
            $quizAnswers[] = [
                'answer' => $answerG,
                'id' => 'G'
            ];
        }

        if ($answerH = $quiz->getAnswerH()) {
            $quizAnswers[] = [
                'answer' => $answerH,
                'id' => 'H'
            ];
        }

        shuffle($quizAnswers);

        return $quizAnswers;
    }

}