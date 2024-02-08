<?php

namespace App\Command;

use App\Repository\CategoryRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fill-category:http-code',
    description: 'Add a short description for your command',
)]
class FillCategoryHttpCodeCommand extends Command
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private QuizRepository $quizRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln('Fill category HTTP Code');

        $categoryHttpCode = $this->categoryRepository->findOneBy(['name' => 'HTTP Code']);

        $quizzes = $this->quizRepository->getNumberLength();

        foreach ($quizzes as $quiz) {
            if (null === $quiz->getCategory()) {
                $quiz->setCategory($categoryHttpCode);
                $this->entityManager->persist($quiz);
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('%d quizzes add to %s', count($quizzes), $categoryHttpCode->getName()));

        return Command::SUCCESS;
    }
}
