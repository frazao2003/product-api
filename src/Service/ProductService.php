<?php

namespace App\Service;
use App\Dto\ProductFilter;
use App\Dto\StockProdFilter;
use App\Repository\ProductRepository;
use App\Service\TypeProductService;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService{

    private ProductRepository $productRepository;
    private TypeProductService $typeProductService;
    private EntityManagerInterface $entityManager;
    private StockProductService $stockProductService;

    public function __construct
    (
        ProductRepository $productRepository,
        TypeProductService $typeProductService,
        EntityManagerInterface $entityManager,
        StockProductService $stockProductService
    )
    {
        $this->productRepository = $productRepository;
        $this->typeProductService = $typeProductService;
        $this->entityManager = $entityManager;
        $this->stockProductService = $stockProductService;
    }

    public function filterProduct(ProductFilter $productFilter):array{
        $products = $this->productRepository->filterProduct($productFilter);
        foreach($products as $product){
            $data [ ] = 
            [
                'product' => $product->toArray(),
            ];
        }
        return $data;
    }

    public function getProductById(int $id):Product{
        $product = $this->productRepository->find($id);
        if(is_null($product)){
            throw new \Exception("Wrong ID, try again");
        }
        return $product;
    }

    public function createProduct(String $nome, int $idTypeProduct):array{
        $productValidate = $this->productRepository->findOneByName($nome);
        if($productValidate){ 
            throw new \Exception("Thats product with this name: $nome is already registred");
        }
        $typeProduct = $this->typeProductService->findById($idTypeProduct);
        if(is_null($typeProduct)){
            throw new \Exception("Product Type with this $idTypeProduct not found");
        }
        $product = new Product();
        $product->setName($nome);
        $product->setTypeProduct($typeProduct);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product->toArray();
    }

    public function updateProduct(int $id, int $idType, String $newNome) :Product
    {
        $productValidate = $this->productRepository->findById($id);
        if(is_null($productValidate))
        {
            throw new \Exception("Product not found");
        }
        $typeProduct = $this->typeProductService->findById($idType);
        if(is_null($typeProduct)){
            throw new \Exception("Product Type not found");
        }

        $productValidate->setName($newNome);
        $productValidate->setTypeProduct($typeProduct);
        $this->entityManager->persist($productValidate);
        $this->entityManager->flush();
        return $productValidate;

    }

    public function deleteProduct(int $id): Product
    {
        try {
            $productValidate = $this->productRepository->findById($id);
            if ($productValidate == null) {
                throw new \Exception("Product with ID: $id not found");
            }
    
            $stockFilter = new StockProdFilter();
            $stockFilter->setProduct($productValidate);
            if (count($this->stockProductService->filterStockProd($stockFilter)) > 0) {
                throw new \Exception("Delete failed, product already vinculated");
            }    
            $this->productRepository->delete($productValidate);
    
            return $productValidate;
        } catch (\Exception $e) {
            throw new \Exception('Error deleting product: '); // Re-throw exception for further handling
        }
    }

}