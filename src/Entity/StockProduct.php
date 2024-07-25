<?php

namespace App\Entity;

use App\Repository\StockProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockProductRepository::class)]
class StockProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quant = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    private ?string $codLote = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expirationDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuant(): ?int
    {
        return $this->quant;
    }

    public function setQuant(int $quant): static
    {
        $this->quant = $quant;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getCodLote(): ?string
    {
        return $this->codLote;
    }

    public function setCodLote(string $codLote): static
    {
        $this->codLote = $codLote;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeInterface $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'=> $this->getId(),
            'quant'=> $this->getQuant(),
            'Product'=> $this->getProduct()->toArray(),
            'lote'=> $this->getCodLote(),
            'expirationDate'=> $this->getExpirationDate(),
        ];
    }
}
