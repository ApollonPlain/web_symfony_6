<?php

namespace App\Service;

use App\Entity\Quiz;
use App\Entity\ResultMCQ;
use Doctrine\ORM\EntityManagerInterface;

class ResultMCQService
{


    public function __construct(private EntityManagerInterface $em)
    {
    }
    public function saveResultMCQ(Quiz $quiz, bool $isCorrect = false)
    {
        $resultMCQ = new ResultMCQ();

        $resultMCQ->setQuiz($quiz);
        $resultMCQ->setIsCorrect($isCorrect);

        $this->em->persist($resultMCQ);
        $this->em->flush();
    }

}