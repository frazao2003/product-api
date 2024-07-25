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

    public function __construct
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
        $typeProduct = null;
        $product = null;
        $stockProdFilter = new StockProdFilter();
        if ($data['codLote']){
            $stockProdFilter->setCodLote($data['codLote']);
        }
        if ($data['ExpirationDate']){
        $stockProdFilter->setExpirationDate(new \DateTimeImmutable($data['ExpirationDate']));
        }
        if($data['idProduct'])
        {
            $product = $this->productService->getProductByid($data['idProduct']);
            $stockProdFilter->setProduct($product);

        }
        if ($data['typeId'])
        {
            $typeProduct = $this->typeProductService->findById($data['typeId']);
            $stockProdFilter->setTypeProduct($typeProduct);

        }

        $product = $this->stockProductService->filterStockProd($stockProdFilter);
        return $this->json([
            'data' => $product,
        ],200);
    }
    #[Route('/stock/product', name: 'input_stock_product', methods: ['POST'])]
    public function inputs(Request $request): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}
        $idProduct = $data['idProduct'];
        $product = null;
        if ($data['idProduct']){
            $product = $this->productService->getProductById($idProduct);
        }
        $date = new \DateTime($data['expirationDate']);

        $entryDTO = new EntryDTO();
        $entryDTO->setCodLote($data['codLote']);
        $entryDTO->setExpirationDate($date);
        $entryDTO->setProduct($product);
        $entryDTO->setQuant($data['quant']);
        $entryDTO->setId($data['id']);

        $product = $this->stockProductService->inputs($entryDTO);
        
        return $this->json([
            'message' => 'Input accepted',
            'data' => $product->toArray(),
        ],201);
    }
    #[Route('/stock/product/{id}/{quant}', name: 'output_stock_product', methods: ['POST'])]
    public function outputs(int $quant, int $id): JsonResponse
    {
        $product = $this->stockProductService->outputs($id, $quant);
        
        return $this->json([
            'message' => 'Output accepted',
            'data'=> $product->toArray()
        ],201);
    }
    #[Route('/stock/product/{id}', name: 'get_byid_stock_product', methods: ['GET'])]
    public function getById(int $id): JsonResponse
    {
        $product = $this->stockProductService->getById($id);

        return $this->json([
            'message' => 'Product Found',
            'data'=> $product->toArray()
        ],200);
    }
    #[Route('/stock/product/filter/daterange', name: 'filter_date_range_stock_product', methods: ['GET'])]
    public function filterDateRange(Request $request): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}
        $startDate = new \DateTimeImmutable($data['startDate']);
        $endDate = new \DateTimeImmutable($data['endDate']);
        $stockProduct = $this->stockProductService->filterDateRange($startDate, $endDate);

        return $this->json([
            'message'=> 'Stock in product found',
            'data'=> $stockProduct
        ],200);
    }



}
