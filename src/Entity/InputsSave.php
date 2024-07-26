<?php

namespace App\Entity;

use App\Repository\InputsSaveRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InputsSaveRepository::class)]
class InputsSave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $inputs = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct(?array $inputsSaveRepository = null, \DateTimeImmutable $created_at = null)
    {
        $this->inputsSaveRepository = $inputsSaveRepository;
        $this->created_at = $created_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function setInputs(array $inputs): static
    {
        $this->inputs = $inputs;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
