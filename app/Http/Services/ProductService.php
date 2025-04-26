<?php

namespace App\Services;

use App\DTOs\PaginatedCollectionDTO;
use App\DTOs\ProductDTO;
use App\DTOs\CollectionDTO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductService
{
    public function getProducts(): CollectionDTO
    {
        $products = Product::with('category')->get()->toArray();
        return CollectionDTO::fromProducts($products);
    }

    public function getPaginatedProducts(int $perPage): PaginatedCollectionDTO
    {
        $products = Product::with('category')->paginate($perPage)->toArray();
        return PaginatedCollectionDTO::fromProducts($products["data"], $perPage,$products["current_page"] );
    }

    public function createProduct(ProductDTO $productDTO): ProductDTO
    {
        DB::beginTransaction();
        try {
            $product = Product::create($productDTO->toArray());
            DB::commit();
            
            return ProductDTO::fromArray($product->load('category')->toArray());
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to create product: ' . $e->getMessage());
        }
    }

    public function updateProduct(int $id, ProductDTO $productDTO): ProductDTO
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->update($productDTO->toArray());
            
            DB::commit();
            return ProductDTO::fromArray($product->load('category')->toArray());
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update product: ' . $e->getMessage());
        }
    }
}
