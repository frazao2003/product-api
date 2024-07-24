<?php

namespace App\Dto;
use App\Entity\TypeProduct;


class ProductFilter
{
     public ?string $name;

     public ?TypeProduct $typeProduct;

     public function __construct(?string $name = null, ?int $idType = null)
     {
         $this->name = $name;
         $this->idType = $idType;
     }
 
     public function getName(): ?string
     {
         return $this->name;
     }
 
     public function setName(?string $name): void
     {
         $this->name = $name;
     }
 
     public function getType(): ?TypeProduct
     {
         return $this->typeProduct;
     }
 
     public function setType(?TypeProduct $typeProduct): void
     {
         $this->typeProduct = $typeProduct;
     }


}