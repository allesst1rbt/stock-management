<?php

namespace App\Services;

use App\DTOs\CategoryDTO;
use App\DTOs\CollectionDTO;
use App\DTOs\PaginatedCollectionDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryService
{
    public function getAllCategories(): CollectionDTO
    {
        $categories = Category::all()->toArray();

        return CollectionDTO::fromCategories($categories);
    }

    public function getCategoriesPaginated(
        string $name,
        int $perPage,
        string $sortBy,
        string $sortOrder,
        int $page = 1
    ): PaginatedCollectionDTO {
        $query = Category::query();

        if ($name) {
            $query->where('name', 'like', '%'.addcslashes($name, '%_').'%');
        }

        $categories = $query->orderBy($sortBy, $sortOrder)->paginate(perPage: $perPage, page: $page)->toArray();

        return PaginatedCollectionDTO::fromCategories($categories['data'], $categories['per_page'], $categories['current_page'], $categories['total']);
    }

    public function createCategory(CategoryDTO $categoryDTO): CategoryDTO
    {
        DB::beginTransaction();
        try {
            $category = Category::create($categoryDTO->toArray());
            DB::commit();

            return CategoryDTO::fromArray($category->toArray());
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to create category: '.$e->getMessage());
        }
    }

    public function updateCategory(int $id, CategoryDTO $categoryDTO): CategoryDTO
    {
        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);
            $category->update($categoryDTO->toArray());

            DB::commit();

            return CategoryDTO::fromArray($category->toArray());
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update category: '.$e->getMessage());
        }
    }
}
