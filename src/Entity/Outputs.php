<?php

namespace App\Entity;

use App\Repository\OutputsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OutputsRepository::class)]
class Outputs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $outputsDTO = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOutputsDTO(): array
    {
        return $this->outputsDTO;
    }

    public function setOutputsDTO(array $outputsDTO): static
    {
        $this->outputsDTO = $outputsDTO;

        return $this;
    }
}
