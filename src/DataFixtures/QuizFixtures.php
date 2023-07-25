<?php

namespace App\DataFixtures;

use App\Entity\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuizFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $quiz = new Quiz();
            $quiz->setQuestion('Question quiz ' . $i);
            $numberAnswer = mt_rand(2, 8);
            $this->setRandomQuizAnswer($quiz, $numberAnswer);
            $this->setRandomQuizAnswerRight($quiz, mt_rand(1, $numberAnswer));
            $manager->persist($quiz);
        }

        $manager->flush();
    }

    private function setRandomQuizAnswer(Quiz &$quiz, int $number): void
    {
        switch ($number) {
            case 2:
            default:
                $quiz->setAnswerA('A');
                $quiz->setAnswerB('B');
                break;
            case 3:
                $quiz->setAnswerA('A');
                $quiz->setAnswerB('B');
                $quiz->setAnswerC('C');
                break;
            case 4:
                $quiz->setAnswerA('A');
                $quiz->setAnswerB('B');
                $quiz->setAnswerC('C');
                $quiz->setAnswerD('D');
                break;
            case 5:
                $quiz->setAnswerA('A');
                $quiz->setAnswerB('B');
                $quiz->setAnswerC('C');
                $quiz->setAnswerD('D');
                $quiz->setAnswerE('E');
                break;
            case 6:
                $quiz->setAnswerA('A');
                $quiz->setAnswerB('B');
                $quiz->setAnswerC('C');
                $quiz->setAnswerD('D');
                $quiz->setAnswerE('E');
                $quiz->setAnswerF('F');
                break;
            case 7:
                $quiz->setAnswerA('A');
                $quiz->setAnswerB('B');
                $quiz->setAnswerC('C');
                $quiz->setAnswerD('D');
                $quiz->setAnswerE('E');
                $quiz->setAnswerF('F');
                $quiz->setAnswerG('G');
                break;
            case 8:
                $quiz->setAnswerA('A');
                $quiz->setAnswerB('B');
                $quiz->setAnswerC('C');
                $quiz->setAnswerD('D');
                $quiz->setAnswerE('E');
                $quiz->setAnswerF('F');
                $quiz->setAnswerG('G');
                $quiz->setAnswerH('H');
                break;
        }
    }

    private function setRandomQuizAnswerRight(Quiz &$quiz, int $responseIs): void
    {
        switch ($responseIs) {
            case 1:
            default:
                $quiz->setIsA(true);
                break;
            case 2:
                $quiz->setIsB(true);
                break;
            case 3:
                $quiz->setIsC(true);
                break;
            case 4:
                $quiz->setIsD(true);
                break;
            case 5:
                $quiz->setIsE(true);
                break;
            case 6:
                $quiz->setIsF(true);
                break;
            case 7:
                $quiz->setIsG(true);
                break;
            case 8:
                $quiz->setIsH(true);
                break;
        }
    }

}
