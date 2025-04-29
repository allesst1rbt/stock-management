<?php

namespace App\Http\Controllers;

use App\DTOs\CategoryDTO;
use App\Http\Requests\GetCategoriesRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $this->validateAdmin();

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

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $this->validateAdmin();

        try {
            $categoryDto = CategoryDTO::fromArray($request->validated());
            $category = $this->categoryService->updateCategory($id , $categoryDto);

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
            $categories = $this->categoryService->getCategoriesPaginated(name: $name, perPage: $perPage, sortBy: $sortBy, sortOrder: $sortOrder, page: $page);

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

    public function delete(int $id): JsonResponse
    {
        $this->validateAdmin();
        try {
            $this->categoryService->deleteCategory($id);

            return response()->json([
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function validateAdmin(): bool | JsonResponse
    {
        $user = Auth::user();
        if ($user->roles  !== "admin") {
            abort(403,'Doesnt have necessary requirements');
        }

        return true;
    }

  
}
