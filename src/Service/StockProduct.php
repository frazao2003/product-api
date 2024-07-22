<?php

namespace App\Service;
use App\Repository\StockProductRepository;

class StockProduct {

    private StockProductRepository $stockProductRepository;

    public function __construct
    (
        StockProductRepository $stockProductRepository
    ) 
    {
        $this->stockProductRepository = $stockProductRepository;
    }

    public function getAll():array 
    {
        return $this->stockProductRepository->getAll();
    }
}