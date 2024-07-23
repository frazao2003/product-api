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

    public function __construct(
        TypeProductRepository $typeProductRepository,
        EntityManagerInterface $entityManager,
        ) {
        $this->typeProductRepository = $typeProductRepository;
        $this->entityManager = $entityManager;
    }

    public function filterTypeProd(TypeProdFilterDto $filter): array{
        $types = $this->typeProductRepository->filterTypeProduct($filter);
        return $types;
    }   

    public function create(String $type): TypeProduct{
        $typeProduct = new TypeProduct();
        $typeProduct->setTypeProduct($type);
        $this->entityManager->persist($typeProduct);
        $this->entityManager->flush();

        return $typeProduct;
    }

    public function update(String $newType, int $id): TypeProduct{
        $typeProduct = $this->typeProductRepository->find($id);
        if(!$typeProduct){
            throw new \Exception("Product type not found");
        }
        $typeProduct->setTypeProduct($newType);
        $this->entityManager->persist($typeProduct);
        $this->entityManager->flush();
        return $typeProduct;
    }

    public function delete(String $typeProduct): void{
        $typeProduct = $this->typeProductRepository->findOneByTypeProduct($typeProduct);
        if(!$typeProduct){
            throw new \Exception("Product type not found");
        }
        $this->entityManager->remove($typeProduct);
    }

    public function findById(int $id){
        $typeProduct = $this->typeProductRepository->findOneById($id);
        if(!$typeProduct)
        {
            throw new \Exception("Product type not found");
        }
        return $typeProduct;
    }
}
