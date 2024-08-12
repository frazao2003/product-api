<?php

namespace App\Entity;

use App\Repository\ProductRepository;
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

    #[ORM\ManyToOne(targetEntity: TypeProduct::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeProduct $typeProduct = null;

    #[ORM\ManyToOne(inversedBy: 'Products')]
    private ?TypeProduct $product_id = null;


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

    public function getProductId(): ?TypeProduct
    {
        return $this->product_id;
    }

    public function setProductId(?TypeProduct $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }
}
