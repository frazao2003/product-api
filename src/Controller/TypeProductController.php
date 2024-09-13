<?php

namespace App\Controller;

use App\Dto\TypeProdFilterDto;
use App\Entity\TypeProduct;
use App\Service\TypeProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;




class TypeProductController extends AbstractController
{
    private TypeProductService $typeProductService;
    public function __construct(TypeProductService $typeProductService)
    {
        $this->typeProductService = $typeProductService;
    }
    
    #[Route('/api/type/products', name: 'create_type_product', methods: ['POST'])]
    #[OA\Post(
        path: "/api/type/products",
        summary: 'Cria um novo tipo de produto',
        requestBody: new OA\RequestBody(
            description: 'Dados do tipo de produto a serem criados',
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'typeProduct', type: 'string', example: 'TypeProduct A'),
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
                        new OA\Property(property: 'message', type: 'string', example: 'Type Product created successfully'),
                        new OA\Property(property: 'data', type: 'object')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Dados inválidos'
            )
        ],tags: ["TypeProduct"]
    )]
    public function create(Request $request): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}
        $typeProduct = $this->typeProductService->create($data['typeProduct']);
        return $this->json([
            'message' => 'Product type created successfully',
            'data' => $typeProduct,
        ],201);
    }
    #[Route('/api/type/products/{id}', name: 'edit_type_product', methods: ['PUT'])]
    #[OA\Put(
        path: "/api/type/products/{id}",
        summary: "Update a product",
        description: "Updates a product by its ID",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the type product to update",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Type Product Updated Successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "product", ref: new Model(type: TypeProduct::class))
                    ]
                )
            )
        ],tags: ["TypeProduct"]
    )]
    public function edit(Request $request, int $id):JsonResponse{
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}
        $newTypeProduct = $data['newTypeProduct']; 
        $typeProduct = $this->typeProductService->update($newTypeProduct, $id);

        return $this->json([
            'message' => 'Product type updated successfully',
            'data' => $typeProduct,
        ],200);
    }
    #[Route('/api/type/products/delete/{id}', name: 'delete_type_product', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/type/products/delete/{id}",
        summary: "Delete a product",
        description: "Deletes a type product by its ID",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the type product to delete",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Type Product Deleted Successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "typeproduct", ref: new Model(type: TypeProduct::class))
                    ]
                )
            )
        ],tags: ["TypeProduct"]
    )]
    public function delete(int $id): JsonResponse{
        
        $data = $this->typeProductService->delete($id);
        return $this->json([
            'message' => 'Product type deleted successfully',
            'data' => $data
        ],200);
    }

    #[Route('/api/type/products', name: 'filter_type_product', methods: ['GET'])]
    #[OA\Get(
        path: "/api/type/products",
        summary: "Filtra tipos de produtos de acordo com os critérios fornecidos",
        parameters: [
            new OA\Parameter(
                name: 'typeProduct',
                in: 'query',
                description: 'Nome do tipo do produto',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de tipos de produtos filtrados",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: new Model(type: TypeProduct::class, groups: ["typeproduct:read"]))
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
        tags: ["TypeProduct"]
        )]
        #[Security(name: "Bearer")]

    public function filterTypeProduct(Request $request): JsonResponse{
        $typeProdDto = new TypeProdFilterDto();
        $typeProdDto->setType($request->query->get("typeProduct"));
        
        $typeProduct = $this->typeProductService->filterTypeProd($typeProdDto);

        return $this->json([
            'message' => 'Product type find successfully',
            'data' => $typeProduct,
        ],200);
    }


    #[Route('/api/type/products/{id}', name: 'get_by_id', methods: ['GET'])]
    #[OA\Get(
        path: "/api/type/products/{id}",
        summary: "Get a type product by id",
        description: "Get a type product by id",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the type product to get",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product Type Find Successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "product", ref: new Model(type: TypeProduct::class))
                    ]
                )
            )
        ],tags: ["TypeProduct"]
    )]
    public function getById(int $id): JsonResponse
    {
        $typeProduct = $this->typeProductService->findById($id);
        if( !$typeProduct ){
            throw new \Exception('Product type not found');
        }
        return $this->json([
            'message'=> 'Product Type found',
            'data' => $typeProduct->toArray()   
        ],200);
    }


}
