<?php

namespace App\Service;

class CheckService
{
    private int $randomCheck = 100;

    public function isCheckQuiz(): bool
    {
        return rand(0, $this->randomCheck) === 0;
    }
}