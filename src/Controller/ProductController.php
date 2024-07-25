<?php

namespace App\Controller;

use App\Dto\ProductFilter;
use App\Service\ProductService;
use App\Service\TypeProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{

    private ProductService $productService;
    private TypeProductService  $typeProductService;
    public function __construct
    (
        ProductService $productService,
        TypeProductService $typeProductService
    )
    {
        $this->productService = $productService;
        $this->typeProductService = $typeProductService;
    }
    #[Route('/product', name: 'app_product', methods: ['GET'])]
    public function filterProduct(Request $request): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}

        $productFilter = new ProductFilter();
        $productFilter->setName($data['name']);

        if($data['idTypeProduct'])
        {
            $typeProduct = $this->typeProductService->findById($data['idTypeProduct']);
            $productFilter->setType($typeProduct);

        }
        $productFilter->setType(null);

        $products = $this->productService->filterProduct($productFilter);

        return $this->json([
            'data' => $products,
        ],200);
    }
    #[Route('/product', name: 'create_product', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}

        $nome = $data['nome'];
        $idTypeProduct = $data['idTypeProduct'];
        $product = $this->productService->createProduct($nome, $idTypeProduct);

        return $this->json([
            'message' => 'Product created successfully',
            'data' => $product,
        ],201);
    }
    #[Route('/product/{id}', name:'delete_product', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $product = $this->productService->deleteProduct($id);
        return $this->json([
            'message'=> 'Product Deleted Successfully',
            'data'=> $product->toArray()
        ],200);
    }
    #[Route('/product/{id}/{idType}', name:'update_product', methods: ['PUT'])]
    public function update(Request $request, int $id, int $idType): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}
        $newNome = $data['name'];
        $product = $this->productService->updateProduct($id, $idType, $newNome);
        return $this->json([
            'message'=> 'Product Updated Successfully',
            'data'=> $product->toArray()
        ],201); 
    }
    #[Route('/product/{id}', name:'get_by_id', methods: ['GET'])]
    public function getByid(int $id): JsonResponse
    {
        $product = $this->productService->getProductByid($id);
        return $this->json([
            'message'=> 'Product found',
            'data'=> $product->toArray()
        ],200); 
    }

}
