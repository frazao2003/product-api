<?php

namespace App\Controller;

use App\Dto\ProductFilter;
use App\Service\ProductService;
use App\Service\TypeProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Entity\Product;

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


    #[Route('/api/product', name: 'app_product', methods: ['GET'])]
    #[OA\Get(
        path: "/api/product",
        summary: "Filtra produtos de acordo com os critérios fornecidos",
        parameters: [
            new OA\Parameter(
                name: 'name',
                in: 'query',
                description: 'Nome do produto',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'idTypeProduct',
                in: 'query',
                description: 'ID do tipo de produto',
                required: false,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de produtos filtrados",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: new Model(type: Product::class, groups: ["product:read"]))
                )
            ),
            new OA\Response(
                response: 400,
                description: "Formato de dados não aceito"
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de produto não encontrado"
            )
        ],
        tags: ["Product"]
        )]
        #[Security(name: "Bearer")]
    public function filterProduct(Request $request): JsonResponse
    {
        $productFilter = new ProductFilter();
        $productFilter->setName($request->query->get("name"));
        $productFilter->setType(null);

        if($request->query->get("idTypeProduct") !== null) 
        {
            $typeProduct = $this->typeProductService->findById($request->query->get("idTypeProduct"));
            $productFilter->setType($typeProduct);

        }


        $products = $this->productService->filterProduct($productFilter);

        return $this->json([
            'products
            ' => $products,
        ],200);
    }
    
    #[Route('/api/product', name: 'create_product', methods: ['POST'])]
    #[OA\Post(
        path: "/api/product",
        summary: 'Cria um novo produto',
        requestBody: new OA\RequestBody(
            description: 'Dados do produto a serem criados',
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'nome', type: 'string', example: 'Produto A'),
                    new OA\Property(property: 'idTypeProduct', type: 'integer', example: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Produto criado com sucesso',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Product created successfully'),
                        new OA\Property(property: 'data', type: 'object')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Dados inválidos'
            )
        ],tags: ["Product"]
    )]
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
            'product' => $product,
        ],201);
    }


    #[Route('/api/product/{id}', name:'delete_product', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/product/{id}",
        summary: "Delete a product",
        description: "Deletes a product by its ID",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the product to delete",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product Deleted Successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "product", ref: new Model(type: Product::class))
                    ]
                )
            )
        ],tags: ["Product"]
    )]
    public function delete(int $id): JsonResponse
    {
        $product = $this->productService->deleteProduct($id);
        return $this->json([
            'message'=> 'Product Deleted Successfully',
            'data'=> $product->toArray()
        ],200);
    }


    #[Route('/api/product/{id}/{idType}', name:'update_product', methods: ['PUT'])]
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

     
    #[Route('/api/productgetbyID/{id}', name:'get_product_by_id', methods: ['GET'])]
    #[OA\Get(
        path: "/api/productgetbyID/{id}",
        summary: "Get a product by id",
        description: "Get a product by id",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the product to get",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product Find Successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "product", ref: new Model(type: Product::class))
                    ]
                )
            )
        ],tags: ["Product"]
    )]
    public function getByid(int $id): JsonResponse
    {
        $product = $this->productService->getProductByid($id);
        return $this->json([
            'message'=> 'Product found',
            'data'=> $product->toArray()
        ],200); 
    }

}
