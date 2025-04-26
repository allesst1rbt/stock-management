<?php

namespace App\Services;

use App\DTOs\CategoryDTO;
use App\DTOs\CollectionDTO;
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

    public function getCategories(): CollectionDTO
    {
        $categories = Category::all()->toArray();
        return CollectionDTO::fromCategories($categories);
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
            throw new Exception('Failed to create category: ' . $e->getMessage());
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
            throw new Exception('Failed to update category: ' . $e->getMessage());
        }
    }
}
