<?php

namespace App\Service;
use App\Dto\EntryDTO;
use App\Dto\StockProdFilter;
use App\Repository\StockProductRepository;
use App\Entity\StockProduct;
use Doctrine\ORM\EntityManagerInterface;

class StockProductService {

    private StockProductRepository $stockProductRepository;
    private EntityManagerInterface $em;

    public function __construct 
    (
        StockProductRepository $stockProductRepository,
        EntityManagerInterface $em
    ) 
    {
        $this->stockProductRepository = $stockProductRepository;
        $this->em = $em;
    }

    public function filterStockProd(StockProdFilter $stockProdFilter):array 
    {
        return $this->stockProductRepository->filterStockProducts($stockProdFilter);
    }

    public function getById(int $id): StockProduct
    {
        $stockProduct = $this->stockProductRepository->find($id);
        if(empty($stockProduct))
        {
            throw new \Exception("Product not found in stock");
        }
        return $stockProduct;
    }

    public function inputs(EntryDTO $entry): StockProduct
    {
        if ($entry->getId())
        {
            $product = $this->getById($entry->getId());
            if(empty($product))
            {
                throw new \Exception("Product not found in stock");
            }
            $product->setQuant($product->getQuant() + $entry->getQuant());
        }
        $product = $this->stockProductRepository->findByCodLote($entry->getCodLote());
        if($product)
        {
            if ($product->getExpirationDate() == $entry->getExpirationDate())
            {
                if ($product->getProduct() == $entry->getProduct())
                {
                    $product->setQuant($entry->getQuant() + $product->getQuant());
                    $this->em->persist($product);
                    $this->em->flush();
                    return $product;
                }
            }
        }
        $product = new StockProduct();
        $product->setCodLote($entry->getCodLote());
        $product->setQuant($entry->getQuant());
        $product ->setExpirationDate($entry->getExpirationDate());
        $product->setProduct($entry->getProduct());
        $this->em->persist($product);
        $this->em->flush();
        return $product;

    }

    public function outputs(int $id, int $quant): StockProduct
    {
        $product = $this->getById($id);
        if(!$product){
            throw new \Exception("Product not found");
        }
        if($product->getQuant() < $quant)
        {
            throw new \Exception("Unavailable quantity");
        }
        $product->setQuant($product->getQuant() - $quant);
        return $product;
    }
}