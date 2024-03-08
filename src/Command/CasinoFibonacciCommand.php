<?php

namespace App\Command;

use App\Service\Roulette;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'casino:fibonacci',
    description: 'Add a short description for your command',
)]
class CasinoFibonacciCommand extends Command
{
    private SymfonyStyle $io;

    private int $loose = 0;
    private int $win = 0;

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    public function simulate($bet, $max)
    {
        $fibo = [1, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377, 610, 987];
        $n = 0;
        $time = 0;

        while ($bet < $max && $bet > 0) {
            $game = new Roulette();

            $game->betEven($fibo[$n]);
            $bet -= $fibo[$n];

            $game->run();

            $earn = $game->result();

            if (0 == $earn) {
                $n = sizeof($fibo) == $n + 1 ? $n : $n + 1;
            } else {
                switch ($n) {
                    case 0:
                        break;
                    case 1:
                        $n = 0;
                        break;
                    default:
                        $n -= 2;
                        break;
                }
            }
            $bet += $earn;
            ++$time;

            $this->io->info(sprintf('time : %d, earn: %d, fibo: %d, money: %d', $time, $earn, $fibo[$n], $bet));
        }

        if ($bet < 0) {
            $this->io->error('Simulation : bet = '.$bet.' | times  = '.$time);
            ++$this->loose;
        } else {
            $this->io->success('Simulation : bet = '.$bet.' | times  = '.$time);
            ++$this->win;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $bet = 100;
        $max = 200;

        for ($i = 1; $i > 0; --$i) {
            $this->simulate($bet, $max);
        }

        $this->io->info('Loose = '.$this->loose);
        $this->io->info('Win = '.$this->win);

        return Command::SUCCESS;
    }
}
