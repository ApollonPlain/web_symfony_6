<?php

namespace App\Command;

use App\Entity\Quiz;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'quizz:parser',
    description: 'Quizz parser for yaml files',
)]
class QuizzParserCommand extends Command
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'File path')
            ->addOption('categoryId', 'c', InputOption::VALUE_OPTIONAL, 'Category Id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getOption('file');
        $categoryId = $input->getOption('categoryId');

        if (!$file) {
            $io->error('You need to provide a file path!');

            return Command::FAILURE;
        }

        $io->note(sprintf('You passed an argument: %s', $file));

        if (!file_exists($file)) {
            $output->writeln('<error>YAML file not found!</error>');

            return Command::FAILURE;
        }

        $category = $this->categoryRepository->find($categoryId);
        if (null !== $categoryId && !$category) {
            $io->error('Category not found!');

            return Command::FAILURE;
        }

        $data = Yaml::parseFile($file);

        foreach ($data['questions'] as $item) {
            $answers = $item['answers'];

            $quiz = Quiz::create(
                $item['question'],
                $answers[0]['value'] ?? null, $answers[0]['correct'] ?? false,
                $answers[1]['value'] ?? null, $answers[1]['correct'] ?? null,
                $answers[2]['value'] ?? null, $answers[2]['correct'] ?? null,
                $answers[3]['value'] ?? null, $answers[3]['correct'] ?? null,
                $answers[4]['value'] ?? null, $answers[4]['correct'] ?? null,
                $answers[5]['value'] ?? null, $answers[5]['correct'] ?? null,
                $answers[6]['value'] ?? null, $answers[6]['correct'] ?? null,
                $answers[7]['value'] ?? null, $answers[7]['correct'] ?? null,
                $item['help'] ?? null,
                $category
            );

            $this->entityManager->persist($quiz);
        }

        $this->entityManager->flush();

        $output->writeln('<info>Quiz questions imported successfully!</info>');

        return Command::SUCCESS;
    }
}
