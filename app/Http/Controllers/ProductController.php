<?php

namespace App\Http\Controllers;

use App\DTOs\ProductDTO;
use App\Http\Requests\GetProductsRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function store(StoreProductRequest $request): JsonResponse
    {
        if (! $this->validateRole('admin')) {
            return response()->json([
                'message' => 'Doesnt have enough permissions to create product',
            ], 401);
        }
        try {
            $productDTO = ProductDTO::fromArray($request->validated());
            $product = $this->productService->createProduct($productDTO);

            return response()->json([
                'message' => 'Product created successfully',
                'data' => $product->toArray(),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(GetProductsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $page = $validated['page'] ?? 1;
        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'name';
        $sortOrder = $validated['sort_order'] ?? 'asc';
        try {
            $filters = [
                'name' => $validated['name'] ?? null,
                'category' => $validated['category'] ?? null,
                'price' => $validated['price'] ?? null,
            ];
            $products = $this->productService->getProductsPaginated($filters, $perPage, $sortBy, $sortOrder, $page);

            return response()->json([
                'message' => 'Products retrieved successfully',
                'data' => $products->toArray(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        if ($this->validateRole('user')) {
            return response()->json([
                'message' => 'Doesnt have enough permissions to update product',
            ], 401);
        }
        try {
            $product = $this->productService->getProductById($id);

            return response()->json([
                'message' => 'Product retrieved successfully',
                'data' => $product->toArray(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update( UpdateProductRequest $request, int $id): JsonResponse
    {
        if ($this->validateRole('user')) {
            return response()->json([
                'message' => 'Doesnt have enough permissions to update product',
            ], 401);
        }
        try {
            $productDTO = ProductDTO::fromArray($request->validated());
            $product = $this->productService->updateProduct($id, $productDTO);

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $product->toArray(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        if (! $this->validateRole('admin')) {
            return response()->json([
                'message' => 'Doesnt have enough permissions to delete product',
            ], 401);
        }
        try {
            $this->productService->deleteProduct($id);

            return response()->json([
                'message' => 'Product deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to delete product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function validateRole(string $role): bool
    {
        $user = Auth::user();
        if ($user->roles === $role) {
            return true;
        }

        return false;
    }
}
