<?php

namespace App\Http\Controllers;

use App\DTOs\ProductDTO;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProductRequest;
use Exception;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $productDTO = ProductDTO::fromArray($request->validated());
            $product = $this->productService->createProduct($productDTO);
            
            return response()->json([
                'message' => 'Product created successfully',
                'data' => $product->toArray()
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
