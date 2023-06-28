<?php

namespace App\Test\Controller;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private QuizRepository $repository;
    private string $path = '/quiz/';

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

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'quiz[question]' => 'Testing',
            'quiz[answerA]' => 'Testing',
            'quiz[isA]' => 'Testing',
            'quiz[answerB]' => 'Testing',
            'quiz[isB]' => 'Testing',
            'quiz[answerC]' => 'Testing',
            'quiz[isC]' => 'Testing',
            'quiz[answerD]' => 'Testing',
            'quiz[isD]' => 'Testing',
            'quiz[answerE]' => 'Testing',
            'quiz[isE]' => 'Testing',
            'quiz[answerF]' => 'Testing',
            'quiz[isF]' => 'Testing',
            'quiz[answerG]' => 'Testing',
            'quiz[category]' => 'Testing',
            'quiz[isG]' => 'Testing',
        ]);

        self::assertResponseRedirects('/quiz/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
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

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Quiz');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
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

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'quiz[question]' => 'Something New',
            'quiz[answerA]' => 'Something New',
            'quiz[isA]' => 'Something New',
            'quiz[answerB]' => 'Something New',
            'quiz[isB]' => 'Something New',
            'quiz[answerC]' => 'Something New',
            'quiz[isC]' => 'Something New',
            'quiz[answerD]' => 'Something New',
            'quiz[isD]' => 'Something New',
            'quiz[answerE]' => 'Something New',
            'quiz[isE]' => 'Something New',
            'quiz[answerF]' => 'Something New',
            'quiz[isF]' => 'Something New',
            'quiz[answerG]' => 'Something New',
            'quiz[category]' => 'Something New',
            'quiz[isG]' => 'Something New',
        ]);

        self::assertResponseRedirects('/quiz/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getQuestion());
        self::assertSame('Something New', $fixture[0]->getAnswerA());
        self::assertSame('Something New', $fixture[0]->getIsA());
        self::assertSame('Something New', $fixture[0]->getAnswerB());
        self::assertSame('Something New', $fixture[0]->getIsB());
        self::assertSame('Something New', $fixture[0]->getAnswerC());
        self::assertSame('Something New', $fixture[0]->getIsC());
        self::assertSame('Something New', $fixture[0]->getAnswerD());
        self::assertSame('Something New', $fixture[0]->getIsD());
        self::assertSame('Something New', $fixture[0]->getAnswerE());
        self::assertSame('Something New', $fixture[0]->getIsE());
        self::assertSame('Something New', $fixture[0]->getAnswerF());
        self::assertSame('Something New', $fixture[0]->getIsF());
        self::assertSame('Something New', $fixture[0]->getAnswerG());
        self::assertSame('Something New', $fixture[0]->getCategory());
        self::assertSame('Something New', $fixture[0]->getIsG());
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
