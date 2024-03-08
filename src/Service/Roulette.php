<?php

namespace App\Service;

class Roulette
{
    private int $number = -1;
    private bool $odd = false;
    private bool $even = false;
    private bool $red = false;
    private bool $black = false;
    private bool $highNumbers = false;
    private bool $lowNumbers = false;
    private int $bet = 0;
    private int $betCountOdd = 0;
    private int $betCountEven = 0;
    private int $betCountRed = 0;
    private int $betCountBlack = 0;
    private int $betCountHighNumbers = 0;
    private int $betCountLowNumbers = 0;
    private int $betCountNumber = 0;

    private int $result;

    public function __construct()
    {
    }

    public function run(): void
    {
        $this->result = rand(0, 36);
    }

    public function result(): int
    {
        $earn = 0;

        if ($this->result == $this->number) {
            $earn += $this->betCountNumber * 36;
        }

        if (0 == $this->result) {
            return $earn;
        } elseif (0 == $this->result % 2) {
            $earn += $this->betCountEven * 2;
        } else {
            $earn += $this->betCountOdd * 2;
        }

        return $earn;
    }

    public function betNumber(int $count, int $number)
    {
        $this->betCountNumber = $count;
        $this->bet += $count;
        $this->number = $number;
    }

    public function betOdd(int $count)
    {
        $this->betCountOdd = $count;
        $this->bet += $count;
    }

    public function betEven(int $count)
    {
        $this->betCountEven = $count;
        $this->bet += $count;
    }

    public function betRed(int $count)
    {
    }

    public function betBlack(int $count)
    {
    }

    public function betHighNumbers(int $count)
    {
    }

    public function betLowNumbers(int $count)
    {
    }
}
