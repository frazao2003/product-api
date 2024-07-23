<?php

namespace App\Dto;

class TypeProdFilterDto
{
    public ?string $type;

    public function __construct(?string $type = null)
    {
        $this->type = $type;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }
}