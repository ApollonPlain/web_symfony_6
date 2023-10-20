<?php

namespace App\Service;

class CheckService
{
    private int $randomCheck = 80;

    public function isCheckQuiz(): bool
    {
        return 0 === rand(0, $this->randomCheck);
    }
}
