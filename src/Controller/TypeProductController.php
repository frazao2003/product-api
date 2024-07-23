<?php

namespace App\Controller;

use App\Dto\TypeProdFilterDto;
use App\Service\TypeProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TypeProductController extends AbstractController
{
    private TypeProductService $typeProductService;
    public function __construct(TypeProductService $typeProductService)
    {
        $this->typeProductService = $typeProductService;
    }
    
    #[Route('/type/products', name: 'create_type_product', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}
        $typeProduct = $this->typeProductService->create($data['typeProduct']);
        return $this->json([
            'message' => 'Product type created successfully',
            'data' => $typeProduct,
        ]);
    }
    #[Route('/type/products', name: 'edit_type_product', methods: ['PUT'])]
    public function edit(Request $request):JsonResponse{
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}
        $typeProductFind = $data['typeProduct'];
        $newTypeProduct = $data['newTypeProduct']; 
        $typeProduct = $this->typeProductService->update($newTypeProduct, $typeProductFind);

        return $this->json([
            'message' => 'Product type updated successfully',
            'data' => $typeProduct,
        ]);
    }
    #[Route('/type/products', name: 'delete_type_product', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse{
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}
        
        $this->typeProductService->delete($data['typeProduct']);
        return $this->json([
            'message' => 'Product type deleted successfully',
        ]);
    }
    #[Route('/type/products', name: 'get_type_product', methods: ['GET'])]
    public function filterTypeProduct(Request $request): JsonResponse{
        if($request -> headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();

        }else{throw new \Exception('Data format not accepted');}
        $typeProdDto = new TypeProdFilterDto();
        $typeProdDto->setType($data['typeProduct']);

        $typeProduct = $this->typeProductService->filterTypeProd($typeProdDto);

        return $this->json([
            'message' => 'Product type find successfully',
            'data' => $typeProduct,
        ]);
    }
    #[Route('/type/products/{id}', name: 'get_type_product', methods: ['GET'])]
    public function getById(int $id): JsonResponse
    {
        $typeProduct = $this->typeProductService->findById($id);
        return $this->json([
            'message'=> 'Product Type found',
            'data' => $typeProduct
        ]);
    }


}
