<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeProduct $typeProduct = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
    public function setTypeProduct(TypeProduct $typeProduct): static
    {
        $this->typeProduct = $typeProduct;

        return $this;
    }
    public function getTypeProduct(): ?TypeProduct
    {
        return $this->typeProduct;
    }
    public function toArray(): array
    {
        return [
            'id'=> $this->getId(),
            'name'=> $this->getName(),
            'type'=> $this->getTypeProduct()->toArray(),
        ];
    }
}
