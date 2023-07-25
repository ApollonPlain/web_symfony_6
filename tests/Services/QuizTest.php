<?php

namespace App\Tests\Services;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use App\Service\QuizService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuizTest extends KernelTestCase
{
    private QuizRepository $repository;

    private QuizService $quizService;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->repository = $container->get('doctrine')->getRepository(Quiz::class);

        // (3) run some service & test the result
        $this->quizService = $container->get(QuizService::class);


//        foreach ($this->repository->findAll() as $object) {
//            $this->repository->remove($object, true);
//        }
    }


    public function testGetRandomizedAnswers()
    {
        self::markTestSkipped('Randomized test');

        $quiz = $this->repository->findAll()[0];

        $this->quizService->getRandomizedAnswers($quiz);
    }

    public function testIsMCQGood()
    {
        $quiz = new Quiz();
        $quiz->setAnswerA('aaaa');
        $quiz->setAnswerB('aaaa');
        $quiz->setAnswerC('aaaa');
        $quiz->setIsA(true);
        $quiz->setIsB(false);
        $quiz->setIsC(false);

        $quizSent = ['A' => true];

        self::assertTrue($this->quizService->isMQCGood($quiz, $quizSent));
    }

    public function testIsMCQGoodWrongAnswer()
    {
        $quiz = new Quiz();
        $quiz->setAnswerA('aaaa');
        $quiz->setAnswerB('aaaa');
        $quiz->setAnswerC('aaaa');
        $quiz->setIsA(true);
        $quiz->setIsB(false);
        $quiz->setIsC(false);

        $quizSent = ['B' => true];

        self::assertFalse($this->quizService->isMQCGood($quiz, $quizSent));
    }

    public function testIsMCQGoodOneGoodAndOneWrongAnswer()
    {
        $quiz = new Quiz();
        $quiz->setAnswerA('aaaa');
        $quiz->setAnswerB('aaaa');
        $quiz->setAnswerC('aaaa');
        $quiz->setIsA(true);
        $quiz->setIsB(false);
        $quiz->setIsC(false);

        $quizSent = [
            'A' => true,
            'B' => true,
        ];

        self::assertFalse($this->quizService->isMQCGood($quiz, $quizSent));
    }

    public function testIsMCQGoodNoSet()
    {
        $quiz = new Quiz();
        $quiz->setAnswerA('aaaa');
        $quiz->setAnswerB('aaaa');
        $quiz->setAnswerC('aaaa');
        $quiz->setIsA(true);
        $quiz->setIsB(false);
        $quiz->setIsC(false);

        $quizSent = [];

        self::assertFalse($this->quizService->isMQCGood($quiz, $quizSent));
    }

    public function testGetRightsAnswers()
    {
        $quiz = new Quiz();
        $quiz->setAnswerA('aaaa');
        $quiz->setAnswerB('aaaa');
        $quiz->setAnswerC('aaaa');
        $quiz->setIsA(true);
        $quiz->setIsB(false);
        $quiz->setIsC(false);

        $arrayExcepted = [
            [
                'answer' => 'aaaa',
                'id' => 'A',
            ]
        ];

        self::assertSame($arrayExcepted, $this->quizService->getRightsAnswers($quiz));


        $quiz->setIsB(true);
        $arrayExcepted[] = [
            'answer' => 'aaaa',
            'id' => 'B',
        ];
        self::assertSame($arrayExcepted, $this->quizService->getRightsAnswers($quiz));

        $quiz->setIsC(true);
        self::assertNotSame($arrayExcepted, $this->quizService->getRightsAnswers($quiz));

    }

}