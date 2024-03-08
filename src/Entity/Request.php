<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $route = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $data = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isMainResquest = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isIsMainResquest(): ?bool
    {
        return $this->isMainResquest;
    }

    public function setIsMainResquest(bool $isMainResquest): static
    {
        $this->isMainResquest = $isMainResquest;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}
