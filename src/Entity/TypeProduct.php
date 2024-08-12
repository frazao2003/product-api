<?php

namespace App\Entity;

use App\Repository\TypeProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeProductRepository::class)]
class TypeProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeProduct = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'product_id')]
    private Collection $Products;

    public function __construct()
    {
        $this->Products = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeProduct(): ?string
    {
        return $this->typeProduct;
    }

    public function setTypeProduct(string $typeProduct): static
    {
        $this->typeProduct = $typeProduct;

        return $this;
    }

    public function toArray(): array
    {
        return [
            "id"=> $this->getId(),
            "type"=> $this->getTypeProduct(),
            ];
        }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->Products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->Products->contains($product)) {
            $this->Products->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->Products->removeElement($product); 
        return $this;
    }
}
