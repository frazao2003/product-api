<?php

namespace App\Controller;

use App\Dto\EntryDTO;
use App\Dto\StockProdFilter;
use App\Service\ProductService;
use App\Service\StockProductService;
use App\Service\TypeProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class StockProductController extends AbstractController
{
    private ProductService $productService;
    private TypeProductService $typeProductService;
    private StockProductService $stockProductService;

    public function construct
    (
        ProductService $productService,
        TypeProductService $typeProductService,
        StockProductService $stockProductService
    ) 
    {
        $this->productService = $productService;
        $this->typeProductService = $typeProductService;
        $this->stockProductService = $stockProductService;
    }
    #[Route('/stock/product', name: 'filter_stock_product', methods: ['GET'])]
    public function filterStockProd(Request $request): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}

        $stockProdFilter = new StockProdFilter();
        $stockProdFilter->setCodLote($data['codLote']);
        $stockProdFilter->setExpirationDate($data['ExpirationDate']);
        $product = $this->productService->getProductByid($data['idProduct']);
        $typeProduct = $this->typeProductService->findById($data['typeId']);
        $stockProdFilter->setProduct($product);
        $stockProdFilter->setTypeProduct($typeProduct);

        $data = $this->stockProductService->filterStockProd($stockProdFilter);
        return $this->json([
            'data' => $data,
        ]);
    }
    #[Route('/stock/product', name: 'input_stock_product', methods: ['POST'])]
    public function inputs(Request $request): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}

        $product = $this->productService->getProductById($data['idProduct']);

        $entryDTO = new EntryDTO( 
           $data['codLote'],
           $product,
           $data['expirationDate'],
           $data['quant'].
           $data['id'],
        );

        $product = $this->stockProductService->inputs($entryDTO);
        
        return $this->json([
            'message' => 'Inputs accepted',
            'data' => $product,
        ]);
    }
    #[Route('/stock/product/{id}/{quant}', name: 'output_stock_product', methods: ['POST'])]
    public function outputs(int $quant, int $id): JsonResponse
    {
        $product = $this->stockProductService->outputs($id, $quant);
        
        return $this->json([
            'message' => 'Output accepted',
            'data'=> $product
        ]);
    }
    #[Route('/stock/product/{id}', name: 'filter_stock_product', methods: ['GET'])]
    public function getById(int $id): JsonResponse
    {
        $product = $this->stockProductService->getById($id);

        return $this->json([
            'message' => 'Product Found',
            'data'=> $product
        ]);
    }

}
