<?php

namespace App\Dto;

class OutputsDTO{

    public ?int $idProduct;
    public ?int $quant;

    public function __construct(int $idProduct = null, int $quant = null) {
        $this->idProduct = $idProduct;
        $this->quant = $quant;
    }

    public function getIdProduct(): int{
        return $this->idProduct;
    }

    public function getQuant(): int{
        return $this->quant;
    }

    public function setQuant(int $quant):void{
        $this->quant = $quant;
    }

    public function setoIdProduct(int $oIdProduct):void{
        $this->oIdProduct = $oIdProduct;
    }

}