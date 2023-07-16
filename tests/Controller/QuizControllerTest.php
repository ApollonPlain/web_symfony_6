<?php

namespace App\Tests\Controller;

use App\Controller\QuizController;
use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private QuizRepository $repository;
    private string $path = '/quiz/';

    private function createController(): QuizController
    {
        return new QuizController();
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Quiz::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Quiz index');

        $controller = $this->createController();
        self::assertTrue(method_exists($controller, 'index'));

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'quiz[question]' => 'Title',
            'quiz[answerA]' => 'Good Answer',
            'quiz[isA]' => true,
            'quiz[answerB]' => 'Testing',
            'quiz[isB]' => false,
            'quiz[answerC]' => 'Testing',
            'quiz[isC]' => false,
            'quiz[answerD]' => 'Testing',
            'quiz[isD]' => false,
            'quiz[answerE]' => 'Testing',
            'quiz[isE]' => false,
            'quiz[answerF]' => 'Testing',
            'quiz[isF]' => false,
            'quiz[answerG]' => 'Testing',
            'quiz[isG]' => false,
            'quiz[answerH]' => 'Testing',
            'quiz[isH]' => false,
        ]);

        self::assertResponseRedirects('/quiz/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Quiz();
        $fixture->setQuestion('My Title');
        $fixture->setAnswerA('Good Answer');
        $fixture->setIsA(true);
        $fixture->setAnswerB('My Title');
        $fixture->setIsB(false);
        $fixture->setAnswerC('My Title');
        $fixture->setIsC(false);
        $fixture->setAnswerD('My Title');
        $fixture->setIsD(false);
        $fixture->setAnswerE('My Title');
        $fixture->setIsE(false);
        $fixture->setAnswerF('My Title');
        $fixture->setIsF(false);
        $fixture->setAnswerG('My Title');
        $fixture->setIsG(false);
        $fixture->setAnswerH('My Title');
        $fixture->setIsH(false);
        $fixture->setCategory(null);
        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path . 'show/', $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Quiz');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = new Quiz();
        $fixture->setQuestion('My Title');
        $fixture->setAnswerA('Wrong Answer');
        $fixture->setIsA(false);
        $fixture->setAnswerB('Good Answer');
        $fixture->setIsB(true);
        $fixture->setAnswerC('My Title');
        $fixture->setIsC(false);
        $fixture->setAnswerD('My Title');
        $fixture->setIsD(false);
        $fixture->setAnswerE('My Title');
        $fixture->setIsE(false);
        $fixture->setAnswerF('My Title');
        $fixture->setIsF(false);
        $fixture->setAnswerG('My Title');
        $fixture->setIsG(false);
        $fixture->setAnswerH('My Title');
        $fixture->setIsH(false);
        $fixture->setCategory(null);

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path . 'q/', $fixture->getId()));

        $this->client->submitForm('Update', [
            'quiz[question]' => 'Title',
            'quiz[answerA]' => 'Good Answer',
            'quiz[isA]' => true,
            'quiz[answerB]' => 'Wrong Answer',
            'quiz[isB]' => false,
            'quiz[answerC]' => 'Testing',
            'quiz[isC]' => false,
            'quiz[answerD]' => 'Testing',
            'quiz[isD]' => false,
            'quiz[answerE]' => 'Testing',
            'quiz[isE]' => false,
            'quiz[answerF]' => 'Testing',
            'quiz[isF]' => false,
            'quiz[answerG]' => 'Testing',
            'quiz[isG]' => false,
            'quiz[answerH]' => 'Testing',
            'quiz[isH]' => false,
        ]);

        self::assertResponseRedirects('/quiz/');

        $fixture = $this->repository->findAll();

        self::assertSame('Title', $fixture[0]->getQuestion());
        self::assertSame('Good Answer', $fixture[0]->getAnswerA());
        self::assertSame(true, $fixture[0]->isIsA());
        self::assertSame('Wrong Answer', $fixture[0]->getAnswerB());
        self::assertSame(false, $fixture[0]->isIsB());
        self::assertSame('Testing', $fixture[0]->getAnswerC());
        self::assertSame(false, $fixture[0]->isIsC());
        self::assertSame('Testing', $fixture[0]->getAnswerD());
        self::assertSame(false, $fixture[0]->isIsD());
        self::assertSame('Testing', $fixture[0]->getAnswerE());
        self::assertSame(false, $fixture[0]->isIsE());
        self::assertSame('Testing', $fixture[0]->getAnswerF());
        self::assertSame(false, $fixture[0]->isIsF());
        self::assertSame('Testing', $fixture[0]->getAnswerG());
        self::assertSame(false, $fixture[0]->isIsG());
        self::assertSame('Testing', $fixture[0]->getAnswerH());
        self::assertSame(false, $fixture[0]->isIsH());
        self::assertSame(null, $fixture[0]->getCategory());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Quiz();
        $fixture->setQuestion('My Title');
        $fixture->setAnswerA('My Title');
        $fixture->setIsA('My Title');
        $fixture->setAnswerB('My Title');
        $fixture->setIsB('My Title');
        $fixture->setAnswerC('My Title');
        $fixture->setIsC('My Title');
        $fixture->setAnswerD('My Title');
        $fixture->setIsD('My Title');
        $fixture->setAnswerE('My Title');
        $fixture->setIsE('My Title');
        $fixture->setAnswerF('My Title');
        $fixture->setIsF('My Title');
        $fixture->setAnswerG('My Title');
        $fixture->setCategory('My Title');
        $fixture->setIsG('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/quiz/');
    }
}
