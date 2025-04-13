<?php

namespace App\Entity;

use App\Repository\DailyQuizProgressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DailyQuizProgressRepository::class)]
class DailyQuizProgress
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $totalAnswered = 0;

    #[ORM\Column]
    private ?int $correctAnswers = 0;

    #[ORM\ManyToOne(inversedBy: 'dailyQuizProgresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $dailyGoal = 20;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTotalAnswered(): ?int
    {
        return $this->totalAnswered;
    }

    public function setTotalAnswered(int $totalAnswered): static
    {
        $this->totalAnswered = $totalAnswered;

        return $this;
    }

    public function getCorrectAnswers(): ?int
    {
        return $this->correctAnswers;
    }

    public function setCorrectAnswers(int $correctAnswers): static
    {
        $this->correctAnswers = $correctAnswers;

        return $this;
    }

    public function incrementTotalAnswered(): static
    {
        ++$this->totalAnswered;

        return $this;
    }

    public function incrementCorrectAnswers(): static
    {
        ++$this->correctAnswers;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDailyGoal(): ?int
    {
        return $this->dailyGoal;
    }

    public function setDailyGoal(int $dailyGoal): static
    {
        $this->dailyGoal = $dailyGoal;

        return $this;
    }

    public function getProgressPercentage(): int
    {
        if ($this->dailyGoal <= 0) {
            return 0;
        }

        $percentage = ($this->totalAnswered / $this->dailyGoal) * 100;

        return min(100, (int) $percentage);
    }

    public function getAccuracyPercentage(): int
    {
        if ($this->totalAnswered <= 0) {
            return 0;
        }

        $percentage = ($this->correctAnswers / $this->totalAnswered) * 100;

        return (int) $percentage;
    }
}
