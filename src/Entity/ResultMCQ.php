<?php

namespace App\Entity;

use App\Repository\ResultMCQRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use function Symfony\Component\Clock\now;

#[ORM\Entity(repositoryClass: ResultMCQRepository::class)]
class ResultMCQ
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetime = null;

    #[ORM\OneToMany(mappedBy: 'results', targetEntity: Quiz::class)]
    private Collection $Quiz;

    #[ORM\ManyToOne(inversedBy: 'resultMCQs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz = null;

    #[ORM\Column]
    private ?bool $isCorrect = null;

    public function __construct()
    {
        $this->Quiz = new ArrayCollection();
        $this->datetime = now();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuiz(): Collection
    {
        return $this->Quiz;
    }

    public function addQuiz(Quiz $quiz): static
    {
        if (!$this->Quiz->contains($quiz)) {
            $this->Quiz->add($quiz);
            $quiz->setResults($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): static
    {
        if ($this->Quiz->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getResults() === $this) {
                $quiz->setResults(null);
            }
        }

        return $this;
    }

    public function setQuiz(?Quiz $quiz): static
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function isIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): static
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }
}
