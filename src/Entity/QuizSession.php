<?php

namespace App\Entity;

use App\Repository\QuizSessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizSessionRepository::class)]
class QuizSession
{
    public const DIFFICULTY_EASY = 'easy';
    public const DIFFICULTY_MEDIUM = 'medium';
    public const DIFFICULTY_HARD = 'hard';

    public const LIVES_BY_DIFFICULTY = [
        self::DIFFICULTY_EASY => 5,
        self::DIFFICULTY_MEDIUM => 3,
        self::DIFFICULTY_HARD => 2,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quizSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $currentLives = null;

    #[ORM\Column]
    private ?int $maxLives = null;

    #[ORM\Column]
    private ?int $currentStreak = 0;

    #[ORM\Column]
    private ?int $bestStreak = 0;

    #[ORM\Column(type: Types::STRING, length: 20)]
    private ?string $difficulty = self::DIFFICULTY_MEDIUM;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\Column]
    private bool $isCompleted = false;

    #[ORM\Column]
    private bool $isWon = false;

    public function __construct()
    {
        $this->startedAt = new \DateTimeImmutable();
        $this->currentStreak = 0;
        $this->bestStreak = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCurrentLives(): ?int
    {
        return $this->currentLives;
    }

    public function setCurrentLives(int $currentLives): static
    {
        $this->currentLives = $currentLives;

        return $this;
    }

    public function getMaxLives(): ?int
    {
        return $this->maxLives;
    }

    public function setMaxLives(int $maxLives): static
    {
        $this->maxLives = $maxLives;

        return $this;
    }

    public function getCurrentStreak(): ?int
    {
        return $this->currentStreak;
    }

    public function setCurrentStreak(int $currentStreak): static
    {
        $this->currentStreak = $currentStreak;
        if ($currentStreak > $this->bestStreak) {
            $this->bestStreak = $currentStreak;
        }

        return $this;
    }

    public function getBestStreak(): ?int
    {
        return $this->bestStreak;
    }

    public function setBestStreak(int $bestStreak): static
    {
        $this->bestStreak = $bestStreak;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        if (!array_key_exists($difficulty, self::LIVES_BY_DIFFICULTY)) {
            throw new \InvalidArgumentException('Invalid difficulty level');
        }

        $this->difficulty = $difficulty;
        $this->maxLives = self::LIVES_BY_DIFFICULTY[$difficulty];
        $this->currentLives = $this->maxLives;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): static
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): static
    {
        $this->isCompleted = $isCompleted;
        if ($isCompleted && !$this->completedAt) {
            $this->completedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function isWon(): bool
    {
        return $this->isWon;
    }

    public function setIsWon(bool $isWon): static
    {
        $this->isWon = $isWon;

        return $this;
    }

    public function loseLife(): static
    {
        if ($this->currentLives > 0) {
            --$this->currentLives;
            $this->currentStreak = 0;

            if (0 === $this->currentLives) {
                $this->setIsCompleted(true);
                $this->setIsWon(false);
            }
        }

        return $this;
    }

    public function incrementStreak(): static
    {
        ++$this->currentStreak;
        if ($this->currentStreak > $this->bestStreak) {
            $this->bestStreak = $this->currentStreak;
        }

        // Bonus life for every 5 correct answers in a row
        if (0 === $this->currentStreak % 5 && $this->currentLives < $this->maxLives) {
            ++$this->currentLives;
        }

        return $this;
    }

    public static function create(User $user, string $difficulty = self::DIFFICULTY_MEDIUM): self
    {
        $session = new self();
        $session->setUser($user);
        $session->setDifficulty($difficulty);

        return $session;
    }
}
