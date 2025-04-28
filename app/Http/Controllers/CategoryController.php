<?php

namespace App\Http\Controllers;

use App\DTOs\CategoryDTO;
use App\Http\Requests\GetCategoriesRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Exception;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    public function store(StoreCategoryRequest $request): JsonResponse
    {

        try {
            $categoryDto = CategoryDTO::fromArray($request->validated());
            $category = $this->categoryService->createCategory($categoryDto);

            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category->toArray(),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create Category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(GetCategoriesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $page = $validated['page'] ?? 1;
        $name = $validated['name'] ?? null;
        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'name';
        $sortOrder = $validated['sort_order'] ?? 'asc';
        try {
            $categories = $this->categoryService->getCategoriesPaginated($name, $perPage, $sortBy, $sortOrder, $page);

            return response()->json([
                'message' => 'Categories retrieved successfully',
                'data' => $categories->toArray(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve Categories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
