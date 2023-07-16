<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $question = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answerA = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isA = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answerB = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isB = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answerC = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isC = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answerD = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isD = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answerE = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isE = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answerF = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isF = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answerG = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isG = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answerH = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isH = null;


    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $category;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $sources = null;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: ResultMCQ::class, orphanRemoval: true)]
    private Collection $resultMCQs;

    public function __construct()
    {
        $this->resultMCQs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswerA(): ?string
    {
        return $this->answerA;
    }

    public function setAnswerA(?string $answerA): static
    {
        $this->answerA = $answerA;

        return $this;
    }

    public function isIsA(): ?bool
    {
        return $this->isA;
    }

    public function setIsA(bool $isA): static
    {
        $this->isA = $isA;

        return $this;
    }

    public function getAnswerB(): ?string
    {
        return $this->answerB;
    }

    public function setAnswerB(?string $answerB): static
    {
        $this->answerB = $answerB;

        return $this;
    }

    public function isIsB(): ?bool
    {
        return $this->isB;
    }

    public function setIsB(?bool $isB): static
    {
        $this->isB = $isB;

        return $this;
    }

    public function getAnswerC(): ?string
    {
        return $this->answerC;
    }

    public function setAnswerC(?string $answerC): static
    {
        $this->answerC = $answerC;

        return $this;
    }

    public function isIsC(): ?bool
    {
        return $this->isC;
    }

    public function setIsC(?bool $isC): static
    {
        $this->isC = $isC;

        return $this;
    }

    public function getAnswerD(): ?string
    {
        return $this->answerD;
    }

    public function setAnswerD(?string $answerD): static
    {
        $this->answerD = $answerD;

        return $this;
    }

    public function isIsD(): ?bool
    {
        return $this->isD;
    }

    public function setIsD(?bool $isD): static
    {
        $this->isD = $isD;

        return $this;
    }

    public function getAnswerE(): ?string
    {
        return $this->answerE;
    }

    public function setAnswerE(?string $answerE): static
    {
        $this->answerE = $answerE;

        return $this;
    }

    public function isIsE(): ?bool
    {
        return $this->isE;
    }

    public function setIsE(?bool $isE): static
    {
        $this->isE = $isE;

        return $this;
    }

    public function getAnswerF(): ?string
    {
        return $this->answerF;
    }

    public function setAnswerF(?string $answerF): static
    {
        $this->answerF = $answerF;

        return $this;
    }

    public function isIsF(): ?bool
    {
        return $this->isF;
    }

    public function setIsF(?bool $isF): static
    {
        $this->isF = $isF;

        return $this;
    }

    public function getAnswerG(): ?string
    {
        return $this->answerG;
    }

    public function setAnswerG(?string $answerG): static
    {
        $this->answerG = $answerG;

        return $this;
    }

    public function isIsG(): ?bool
    {
        return $this->isG;
    }

    public function setIsG(?bool $isG): static
    {
        $this->isG = $isG;

        return $this;
    }

    public function getAnswerH(): ?string
    {
        return $this->answerH;
    }

    public function setAnswerH(?string $answerH): static
    {
        $this->answerH = $answerH;

        return $this;
    }

    public function isIsH(): ?bool
    {
        return $this->isH;
    }

    public function setIsH(?bool $isH): static
    {
        $this->isH = $isH;

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(?int $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getSources(): ?string
    {
        return $this->sources;
    }

    public function setSources(?string $sources): static
    {
        $this->sources = $sources;

        return $this;
    }

    /**
     * @return Collection<int, ResultMCQ>
     */
    public function getResultMCQs(): Collection
    {
        return $this->resultMCQs;
    }

    public function addResultMCQ(ResultMCQ $resultMCQ): static
    {
        if (!$this->resultMCQs->contains($resultMCQ)) {
            $this->resultMCQs->add($resultMCQ);
            $resultMCQ->setQuiz($this);
        }

        return $this;
    }

    public function removeResultMCQ(ResultMCQ $resultMCQ): static
    {
        if ($this->resultMCQs->removeElement($resultMCQ)) {
            // set the owning side to null (unless already changed)
            if ($resultMCQ->getQuiz() === $this) {
                $resultMCQ->setQuiz(null);
            }
        }

        return $this;
    }
}
