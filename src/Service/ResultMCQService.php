<?php

namespace App\Service;

use App\Entity\DailyQuizProgress;
use App\Entity\Quiz;
use App\Entity\ResultMCQ;
use App\Entity\User;
use App\Repository\DailyQuizProgressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ResultMCQService
{
    public function __construct(
        private EntityManagerInterface $em,
        private DailyQuizProgressRepository $dailyQuizProgressRepository,
        private Security $security,
    ) {
    }

    public function saveResultMCQ(Quiz $quiz, bool $isCorrect = false): ResultMCQ
    {
        $resultMCQ = new ResultMCQ();
        $resultMCQ->setQuiz($quiz);
        $resultMCQ->setIsCorrect($isCorrect);

        $this->em->persist($resultMCQ);

        // Update daily progress if user is logged in
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $this->updateDailyProgress($user, $isCorrect);
        }

        $this->em->flush();

        return $resultMCQ;
    }

    private function updateDailyProgress(User $user, bool $isCorrect): void
    {
        $dailyProgress = $this->dailyQuizProgressRepository->findTodayProgressForUser($user);

        if (!$dailyProgress) {
            $dailyProgress = new DailyQuizProgress();
            $dailyProgress->setUser($user);
            $dailyProgress->setDate(new \DateTime());
            $this->em->persist($dailyProgress);
        }

        $dailyProgress->incrementTotalAnswered();

        if ($isCorrect) {
            $dailyProgress->incrementCorrectAnswers();
        }
    }

    public function getTodayProgressForCurrentUser(): ?DailyQuizProgress
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return null;
        }

        $dailyProgress = $this->dailyQuizProgressRepository->findTodayProgressForUser($user);

        if (!$dailyProgress) {
            $dailyProgress = new DailyQuizProgress();
            $dailyProgress->setUser($user);
            $dailyProgress->setDate(new \DateTime());
            $this->em->persist($dailyProgress);
            $this->em->flush();
        }

        return $dailyProgress;
    }
}
