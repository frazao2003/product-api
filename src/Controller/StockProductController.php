<?php

namespace App\Controller;

use App\Dto\EntryDTO;
use App\Dto\OutputsDTO;
use App\Dto\StockProdFilter;
use App\Entity\StockProduct;
use App\Service\ProductService;
use App\Service\StockProductService;
use App\Service\TypeProductService;
use OpenApi\Annotations\RequestBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;



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
    #[Route('/api/stock/product', name: 'filter_stock_product', methods: ['GET'])]
    #[OA\Get(
        path: '/api/stock/product',
        summary: 'Filtra produtos em estoque com base em critérios fornecidos',
        description: 'Filtra produtos em estoque de acordo com os critérios fornecidos nos parâmetros de consulta.',
        operationId: 'filterStockProduct',
        parameters: [
            new OA\Parameter(
                name: 'codLote',
                in: 'query',
                description: 'Código de lote',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'ExpirationDate',
                in: 'query',
                description: 'Data de vencimento',
                required: false,
                schema: new OA\Schema(type: 'date', format: 'date-time')
            ),
            new OA\Parameter(
                name: 'idProduct',
                in: 'query',
                description: 'ID do produto',
                required: false,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'typeId',
                in: 'query',
                description: 'ID do tipo de produto',
                required: false,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Produtos em estoque filtrados com sucesso',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                        property: 'id',
                                        type: 'integer',
                                        description: 'ID do produto em estoque'
                                    ),
                                    new OA\Property(
                                        property: 'codLote',
                                        type: 'string',
                                        description: 'Código de lote'
                                    ),
                                    new OA\Property(
                                        property: 'ExpirationDate',
                                        type: 'string',
                                        format: 'date-time',
                                        description: 'Data de vencimento'
                                    ),
                                    new OA\Property(
                                        property: 'Product',
                                        type: 'object',
                                        description: 'Produto',
                                        properties: [
                                            new OA\Property(
                                                property: 'id',
                                                type: 'integer',
                                                description: 'ID do produto'
                                            ),
                                            new OA\Property(
                                                property: 'name',
                                                type: 'string',
                                                description: 'Nome do produto'
                                            ),
                                            new OA\Property(
                                                property: 'TypeProduct',
                                                type: 'object',
                                                description: 'Tipo do produto',
                                                properties: [
                                                    new OA\Property(
                                                        property: 'id',
                                                        type: 'integer',
                                                        description: 'ID do tipo do produto'
                                                    ),
                                                    new OA\Property(
                                                        property: 'name',
                                                        type: 'string',
                                                        description: 'Nome do tipo do produto'
                                                    )
                                                ]
                                            )
                                        ]
                                    ),
                                    new OA\Property(
                                        property: 'Quant',
                                        type: 'integer',
                                        description: 'Quantidade'
                                    )
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Dados de entrada inválidos',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Dados de entrada inválidos'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 406,
                description: 'Formato de dados não aceito',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Formato de dados não aceito'
                        )
                    ]
                )
            )
        ],
        tags: ['StockProduct']
    )]
    public function filterStockProd(Request $request): JsonResponse
    {
        $typeProduct = null;
        $product = null;
        $stockProdFilter = new StockProdFilter();
        if ($request->query->get("codLote")){
            $stockProdFilter->setCodLote($request->query->get("codLote"));
        }
        if ($request->query->get("ExpirationDate")){
        $stockProdFilter->setExpirationDate(new \DateTimeImmutable($request->query->get("ExpirationDate")));
        }
        if($request->query->get("idProduct"))
        {
            $product = $this->productService->getProductByid($request->query->get("idProduct"));
            $stockProdFilter->setProduct($product);

        }
        if ($request->query->get("typeId"))
        {
            $typeProduct = $this->typeProductService->findById($request->query->get("typeId"));
            $stockProdFilter->setTypeProduct($typeProduct);

        }

        $product = $this->stockProductService->filterStockProd($stockProdFilter);
        return $this->json([
            'data' => $product,
        ],200);
    }

    #[Route('/api/stock/product', name: 'input_stock_product', methods: ['POST'])]
    #[OA\Post(
        path: '/api/stock/product',
        summary: 'Adiciona entradas de estoque para um produto',
        description: 'Adiciona entradas de estoque para um produto com base nos dados fornecidos no corpo da solicitação.',
        requestBody: new OA\RequestBody(
            description: 'Dados da entrada',
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['items'],
                properties: [
                    new OA\Property(
                        property: 'items',
                        type: 'array',
                        items: new OA\Items(
                            type: 'object',
                            required: ['id', 'quant', 'CodLote', 'ExpirationDate'],
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'integer',
                                    description: 'ID do item',
                                    example: 1
                                ),
                                new OA\Property(
                                    property: 'quant',
                                    type: 'integer',
                                    description: 'Quantidade do item',
                                    example: 10
                                ),
                                new OA\Property(
                                    property: 'CodLote',
                                    type: 'string',
                                    description: 'Código de lote',
                                    example: 'ABC123'
                                ),
                                new OA\Property(
                                    property: 'ExpirationDate',
                                    type: 'string',
                                    format: 'date-time',
                                    description: 'Data de vencimento',
                                    example: '2024-12-31T23:59:59Z'
                                ),
                                new OA\Property(
                                    property: 'idProduct',
                                    type: 'integer',
                                    description: 'ID do produto',
                                    example: 1
                                )
                            ]
                        )
                    )
                ]
            )
        ),
        tags: ['StockProduct'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Entrada aceita',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Input accepted'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            description: 'Dados retornados',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                        property: 'productId',
                                        type: 'integer',
                                        description: 'ID do produto',
                                        example: 1
                                    ),
                                    new OA\Property(
                                        property: 'quant',
                                        type: 'integer',
                                        description: 'Quantidade da entrada',
                                        example: 1
                                    ),
                                    new OA\Property(
                                        property: 'expirationDate',
                                        type: 'string',
                                        format: 'date-time',
                                        description: 'Data de validade',
                                        example: '2024-12-31T23:59:59Z'
                                    ),
                                    new OA\Property(
                                        property: 'status',
                                        type: 'string',
                                        description: 'Status do processamento',
                                        example: 'success'
                                    )
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Dados de entrada inválidos',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Invalid input data'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 415,
                description: 'Formato de dados não aceito',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Data format not accepted'
                        )
                    ]
                )
            )
        ]
    )]
    public function inputs(Request $request): JsonResponse
    {
        if ($request->headers->get('Content-Type') !== 'application/json') {
            return new JsonResponse(['message' => 'Data format not accepted'], 415);
        }

        $data = $request->toArray();

        if (!isset($data['items']) || !is_array($data['items'])) {
            return new JsonResponse(['message' => 'Invalid input data'], 400);
        }

        $items = $data['items'];
        $entrysDTO = [];
        foreach ($items as $item) {
            if (!isset($item['id'], $item['quant'], $item['CodLote'], $item['ExpirationDate'])) {
                return new JsonResponse(['message' => 'Invalid input data'], 400);
            }

            $idProduct = $item['idProduct'] ?? null;
            $product = null;
            if ($idProduct) {
                $product = $this->productService->getProductById($idProduct);
            }
            $date = new \DateTime($item['ExpirationDate']);

            $entryDTO = new EntryDTO();
            $entryDTO->setCodLote($item['CodLote']);
            $entryDTO->setExpirationDate($date);
            $entryDTO->setProduct($product);
            $entryDTO->setQuant($item['quant']);
            $entryDTO->setId($item['id']);
            $entrysDTO[] = $entryDTO;
        }

        $product = $this->stockProductService->inputs($entrysDTO);

        return new JsonResponse([
            'message' => 'Input accepted',
            'data' => $product
        ], 201);
    }

    #[Route('/api/stock/product/outputs', name: 'output_stock_product', methods: ['POST'])]
    #[OA\Post(
        path: '/api/stock/product/outputs',
        summary: 'Processa saídas de estoque para um produto',
        description: 'Processa saídas de estoque com base nos dados fornecidos no corpo da solicitação.',
        operationId: 'outputStockProduct',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['items'],
                properties: [
                    new OA\Property(
                        property: 'items',
                        type: 'array',
                        items: new OA\Items(
                            type: 'object',
                            required: ['quant'],
                            properties: [
                                new OA\Property(
                                    property: 'quant',
                                    type: 'integer',
                                    description: 'Quantidade a ser retirada',
                                    example: 5
                                ),
                                new OA\Property(
                                    property: 'idProduct',
                                    type: 'integer',
                                    description: 'ID do produto',
                                    example: 1
                                )
                            ]
                        )
                    )
                ]
            )
        ),
        tags: ['StockProduct']
    )]
    #[OA\Response(
        response: 201,
        description: 'Saída aceita',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'message',
                    type: 'string',
                    example: 'Output accepted'
                ),
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    description: 'Dados retornados',
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            description: 'Status do processamento',
                            example: 'success'
                        ),
                        new OA\Property(
                            property: 'processedOutputs',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                        property: 'idProduct',
                                        type: 'integer',
                                        description: 'ID do produto',
                                        example: 1
                                    ),
                                    new OA\Property(
                                        property: 'quant',
                                        type: 'integer',
                                        description: 'Quantidade processada',
                                        example: 5
                                    )
                                ]
                            )
                        )
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Dados de entrada inválidos',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'message',
                    type: 'string',
                    example: 'Invalid input data'
                )
            ]
        )
    )]
    #[OA\Response(
        response: 415,
        description: 'Formato de dados não aceito',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'message',
                    type: 'string',
                    example: 'Data format not accepted'
                )
            ]
        )
    )]
    public function outputs(Request $request): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}

        if (!isset($data['items']) && is_array($data['items']))
        {
            return new JsonResponse(['message' => 'Invalid input data'], 400);
        }
        $itens = $data['items'];
        $outputs = [];
        foreach ($itens as $data)
        {
            $idProduct = $data['idProduct'];
            $quant = $data['quant'];
            $outputsDto = new OutputsDTO();
            $outputsDto->setoIdProduct($idProduct);
            $outputsDto->setQuant($quant);
            $outputs [] = $outputsDto;
        }

        $product = $this->stockProductService->outputs($outputs);
        
        return $this->json([
            'message' => 'Output accepted',
            'data'=> $product
        ],201);
    }
    #[Route('/api/stock/product/{id}', name: 'get_byid_stock_product', methods: ['GET'])]
    #[OA\Get(
        path: '/stock/product/filter/daterange',
        summary: 'Filter stock products by date range',
        description: 'Retrieve stock products filtered by a start and end date.',
        parameters: [
            new OA\Parameter(
                name: 'startDate',
                in: 'query',
                description: 'The start date of the filter range.',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\Parameter(
                name: 'endDate',
                in: 'query',
                description: 'The end date of the filter range.',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date')
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Stock products found',
                content: new OA\Content(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Stock products found'),
                            new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object'))
                        ]
                    )
                )
            ),
            new OA\Response(
                response: '400',
                description: 'Invalid input',
                content: new OA\Content(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Invalid input')
                        ]
                    )
                )
            )
        ]
    )]
    public function getById(int $id): JsonResponse
    {
        $product = $this->stockProductService->getById($id);

        return $this->json([
            'message' => 'Product Found',
            'data'=> $product->toArray()
        ],200);
    }
    #[Route('/api/stock/product/filter/daterange', name: 'filter_date_range_stock_product', methods: ['GET'])]
    #[OA\Get(
        path: '/api/stock/product/filter/daterange',
        summary: 'Filter stock products by date range',
        description: 'Retrieve stock products filtered by a start and end date.',
        parameters: [
            new OA\Parameter(
                name: 'startDate',
                in: 'query',
                description: 'The start date of the filter range.',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\Parameter(
                name: 'endDate',
                in: 'query',
                description: 'The end date of the filter range.',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date')
            )
        ],tags: ['StockProduct'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Stock products found',
                content: new OA\Content(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Stock products found'),
                            new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object'))
                        ]
                    )
                )
            ),
            new OA\Response(
                response: '400',
                description: 'Invalid input',
                content: new OA\Content(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Invalid input')
                        ]
                    )
                )
            )
        ]
    )]
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
    #[Route('/api/stock/product/expirated/products', name: 'expiration_products_stock_product', methods: ['GET'])]
    #[OA\Get(
        path: '/stock/product/expirated/products',
        summary: 'Get all expired products',
        description: 'Retrieve all products that have expired.',
        responses: [
            new OA\Response(
                response: '200',
                description: 'All expired products in stock',
                content: new OA\Content(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'All expired products in stock'),
                            new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object'))
                        ]
                    )
                )
            ),
            new OA\Response(
                response: '404',
                description: 'No expired products found',
                content: new OA\Content(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'No expired products found')
                        ]
                    )
                )
            )
        ],tags: ['StockProduct']
    )]
    public function getAllExpiratedProd(): JsonResponse
    {
        $data = $this->stockProductService->getAllProdExpirated();
        return $this->json([
            'message'=> 'All expirated products in stock',
            'data'=> $data
        ],200);
        
    }
}
