<?php

namespace App\Service;
use App\Dto\InputsDTO;
use App\Dto\StockProdFilter;
use App\Entity\Outputs;
use App\Repository\StockProductRepository;
use App\Entity\StockProduct;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\InputsSave;

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
        $stockProducts = $this->stockProductRepository->filterStockProducts($stockProdFilter);
        foreach ($stockProducts as $stockProduct) 
        {
            $data [] = 
            [
                "Product in Stock" => $stockProduct->toArray(),
            ];
        }
        if (empty($data))
        {
            throw new \Exception("Not found");
        }
        return $data;
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

    public function inputs(array $entrys): array
    {
        $data = [];
        $inputsSave [] = [];
        foreach($entrys as $entry)
        {
            $inputsDTO = new InputsDTO();
            if ($entry->getId())
            {
                $product = $this->getById($entry->getId());
                if(empty($product))
                {
                    throw new \Exception("Product not found in stock");
                }
                $product->setQuant($product->getQuant() + $entry->getQuant());
                $this->em->persist($product);
                $inputsDTO->setQuant($entry->getQuant());
                $inputsDTO->setIdProductInStock($product->getId());
                $inputsSave [] = $inputsDTO;
                $data[] = $product->toArray();
                continue;
            }
            if($entry->getExpirationDate() < new \DateTime('now'))
            {
                throw new \Exception('Expiration date not valid');
            }
            if($entry->getCodLote())
            {
                $product = $this->stockProductRepository->findByCodLote($entry->getCodLote());
            }
            if($product)
            {
                if ($product->getExpirationDate() == $entry->getExpirationDate() && $product->getProduct() == $entry->getProduct())
                {

                    $product->setQuant($entry->getQuant() + $product->getQuant());
                    $this->em->persist($product);
                    $inputsDTO->setQuant($entry->getQuant());
                    $inputsDTO->setIdProductInStock($product->getId());
                    $inputsSave [] = $inputsDTO;
                    $data[] = $product->toArray();
                    continue;
            
                }
            }
            $newProduct = new StockProduct();
            $newProduct->setCodLote($entry->getCodLote());
            $newProduct->setQuant($entry->getQuant());
            $newProduct ->setExpirationDate($entry->getExpirationDate());
            $newProduct->setProduct($entry->getProduct());
            $this->em->persist($newProduct);
            
            $inputsDTO->setQuant($entry->getQuant());
            $inputsDTO->setIdProductInStock($newProduct->getId());
            $inputsSave [] = $inputsDTO;

            $data[] = $product->toArray();
            
        }
        
        $inputs = new InputsSave();
        $inputs->setInputs($inputsSave);
        $inputs->setCreatedAt(new \DateTimeImmutable('now'));

        $this->em->persist($inputs);
        $this->em->flush();
        return $data;
    }

    public function outputs(array $outputs): array
    {
        $outputs = new Outputs;
        $outputsArray = [];
        foreach($outputs as $outputsDTO) {
            $product = $this->getById($outputsDTO->getIdProduct());
            if(!$product){
                throw new \Exception("Product not found");
            }
            if($product->getQuant() < $outputsDTO->getQuant())
            {
                throw new \Exception("Unavailable quantity");
            }
            $product->setQuant($product->getQuant() - $outputsDTO->getQuant());
            $this->em->persist($product);
            $outputsArray [] = $outputsDTO;
        }
        $outputs->setOutputsDTO($outputsArray);
        $this->em->persist($outputs);
        $this->em->flush();
        return $outputsArray;
    }

    public function filterDateRange(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array
    {
        $stockProducts = $this->stockProductRepository->filterDateRange($startDate, $endDate);
        if(!$stockProducts){
            throw new \Exception('Product in stock not found in this date range');
        }
        foreach ($stockProducts as $stockProduct){
            $data [] = [
                $stockProduct->toArray(),
            ];
        }
        return $data;
    }

    public function getAllProdExpirated():array
    {
        $stockProducts = $this->stockProductRepository->expiratedProd();
        foreach ($stockProducts as $stockProduct){
            $data [] = $stockProduct->toArray();
        }
        return $data;
    }
}