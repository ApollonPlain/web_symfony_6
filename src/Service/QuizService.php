<?php

namespace App\Service;

use App\Entity\Quiz;

class QuizService
{
    public function getRandomizedAnswers(Quiz $quiz): array
    {
        $quizAnswers[] = [
            'answer' => $quiz->getAnswerA(),
            'id' => 'A'
        ];

        if ($quiz->getAnswerB() !== null) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerB(),
                'id' => 'B'
            ];
        }

        if ($quiz->getAnswerC() !== null) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerC(),
                'id' => 'C'
            ];
        }

        if ($quiz->getAnswerD() !== null) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerD(),
                'id' => 'D'
            ];
        }

        if ($quiz->getAnswerE() !== null) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerE(),
                'id' => 'E'
            ];
        }

        if ($quiz->getAnswerF() !== null) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerF(),
                'id' => 'F'
            ];
        }

        if ($quiz->getAnswerG() !== null) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerG(),
                'id' => 'G'
            ];
        }

        if ($quiz->getAnswerH() !== null) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerH(),
                'id' => 'H'
            ];
        }

        shuffle($quizAnswers);

        return $quizAnswers;
    }


    public function isMQCGood(Quiz $quiz, array $quizSent)
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
                'id' => 'B'
            ];
        }

        if ($quiz->isIsB()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerB(),
                'id' => 'B'
            ];
        }

        if ($quiz->isIsC()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerC(),
                'id' => 'C'
            ];
        }

        if ($quiz->isIsD()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerD(),
                'id' => 'D'
            ];
        }

        if ($quiz->isIsE()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerE(),
                'id' => 'E'
            ];
        }

        if ($quiz->isIsF()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerF(),
                'id' => 'F'
            ];
        }

        if ($quiz->isIsG()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerG(),
                'id' => 'G'
            ];
        }

        if ($quiz->isIsH()) {
            $quizAnswers[] = [
                'answer' => $quiz->getAnswerH(),
                'id' => 'H'
            ];
        }

        return $quizAnswers ?? [];
    }

}