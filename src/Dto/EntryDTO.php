<?php

namespace App\Dto;
use App\Entity\Product;
use DateTime;

class EntryDTO{

    public string $codLote;
    public Product $product;
    public DateTime $expirationDate;
    public int $quant;
    public ?int $id;

    public function __construct(?string $codLote = null, ?Product $product=null, ?DateTime $expirationDate=null, ?int $quant = null, ?int $id = null) {
        $this->codlote = $codLote;
        $this->product = $product;
        $this->expirationDate = $expirationDate;
        $this->quant = $quant;
        $this->id = $id;
    }

    public function getCodLote(): ?string
    {
        return $this->codLote;
    }
    public function getProduct(): ?Product
    {
        return $this->product;
    }
    public function getExpirationDate(): ?DateTime
    {
        return $this->expirationDate;
    }
    public function getQuant(): int
    {
        return $this->quant;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setCodLote(string $codLote): void
    {
        $this->codLote = $codLote;
    }
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }
    public function setExpirationDate(DateTime $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }
    public function setQuant(int $quant): void
    {
       $this->quant = $quant;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }


}