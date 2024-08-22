<?php

namespace App\Service;
use App\Dto\TypeProdFilterDto;
use App\Repository\TypeProductRepository;
use App\Entity\TypeProduct;
use Doctrine\ORM\EntityManagerInterface;


final class TypeProductService 
{
    private TypeProductRepository $typeProductRepository;
    private EntityManagerInterface $entityManager;
    private StockProductService $stockProductService;

    public function __construct(
        TypeProductRepository $typeProductRepository,
        EntityManagerInterface $entityManager,
        StockProductService $stockProductService
        ) {
        $this->typeProductRepository = $typeProductRepository;
        $this->entityManager = $entityManager;
        $this->stockProductService = $stockProductService;
    }

    public function filterTypeProd(TypeProdFilterDto $filter): array{
        $types = $this->typeProductRepository->filterTypeProduct($filter);
        foreach($types as $type)
        {
            $data [] = 
            [
                'ProductType' => $type->toArray()
            ];
        }

        return $data;
    }   

    public function create(String $type): array{
        $typeProduct = new TypeProduct();
        $typeProduct->setTypeProduct($type);
        $this->entityManager->persist($typeProduct);
        $this->entityManager->flush();

        $data = $typeProduct->toArray();

        return $data;
    }

    public function update(String $newType, int $id): array{
        $typeProduct = $this->typeProductRepository->find($id);
        if(!$typeProduct){
            throw new \Exception("Product type not found");
        }
        $typeProduct->setTypeProduct($newType);
        $this->entityManager->persist($typeProduct);
        $this->entityManager->flush();
        return $typeProduct->toArray();
    }

    public function delete(int $id): array{
        $typeProduct = $this->typeProductRepository->find( $id );
        if(!$typeProduct){
            throw new \Exception("Product type not found");
        }
        if(count($typeProduct->getProducts()) > 0)
        {
            throw new \Exception("Product Type is already vinculated");
        }
        $this->entityManager->remove($typeProduct);
        $this->entityManager->flush();
        $data = $typeProduct->toArray();
        return $data;
    }
    public function findById(int $id):TypeProduct{
        $typeProduct = $this->typeProductRepository->findOneById($id);
        if(!$typeProduct)
        {
            throw new \Exception("Product type not found");
        }
        return $typeProduct;
    }
}
