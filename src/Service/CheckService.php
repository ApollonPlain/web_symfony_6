<?php

namespace App\Service;

class CheckService
{
    private int $randomCheck = 40;

    public function isCheckQuiz(): bool
    {
        return 0 === rand(0, $this->randomCheck);
    }
}
