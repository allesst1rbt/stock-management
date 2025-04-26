<?php

namespace App\Http\Controllers;

use App\DTOs\CategoryDTO;
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
            $categoryDto = CategoryDTO::fromArray($request->all());
            $category = $this->categoryService->createCategory($categoryDto);
            
            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category->toArray()
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create Category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getCategories();

            return response()->json([
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve Categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}