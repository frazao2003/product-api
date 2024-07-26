<?php

namespace App\Dto;

class InputsDTO
{
    public int $idProductInStock;
    public int $quant;

    public function __construct(int $idProductInStock = null, int $quant) {
        $this->idProductInStock = $idProductInStock;
        $this->quant = $quant;
    }

    public function getIdProductInStock(): int
    {
        return $this->idProductInStock;
    }

    public function getQuant(): int
    {
        return $this->quant;
    }

    public function setIdProductInStock(int $idProductInStock): void
    {
        $this->idProductInStock = $idProductInStock;
    }

    public function setQuant(int $quant): void
    {
        $this->quant = $quant;
    }
}