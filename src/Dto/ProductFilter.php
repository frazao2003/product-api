<?php

namespace App\Dto;


class ProductFilter
{
     public ?string $name;

     public ?int $idType;

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
 
     public function getIdType(): ?int
     {
         return $this->idType;
     }
 
     public function setIdType(?int $idType): void
     {
         $this->idType = $idType;
     }


}