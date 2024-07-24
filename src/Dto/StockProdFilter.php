<?php

namespace App\Dto;
use App\Entity\Product;
use App\Entity\TypeProduct;

class StockProdFilter
{
    public ?string $codLote;

    public ?\DateTimeImmutable $expirationDate;

    public ?Product $product;

    public ?TypeProduct $typeProduct;

    public function __construct(?string $codLote = null, ?\DateTimeImmutable $expirationDate = null, ?Product $product = null)
    {
        $this->codLote = $codLote;
        $this->expirationDate = $expirationDate;
        $this->product = $product;
    }

    public function getCodLote(): ?string
    {
        return $this->codLote;
    }

    public function setCodLote(?string $codLote): void
    {
        $this->codLote = $codLote;
    }

    public function getExpirationDate(): ?\DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?\DateTimeImmutable $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    public function getTypeProduct(): ?TypeProduct
    {
        return $this->typeProduct;
    }

    public function setTypeProduct(?TypeProduct $typeProduct): void
    {
        $this->typeProduct = $typeProduct;
    }
}