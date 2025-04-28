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

    public function getProductsPaginated(
        array $filters,
        int $perPage,
        string $sortBy,
        string $sortOrder,
        int $page = 1
    ): PaginatedCollectionDTO {
        $query = Product::with('category')
            ->when(
                ! empty($filters['name']),
                fn ($q) => $q->where('name', 'like', '%'.addcslashes($filters['name'], '%_').'%')
            )
            ->when(
                ! empty($filters['price']),
                fn ($q) => $q->where('price', 'like', '%'.addcslashes($filters['price'], '%_').'%')
            )
            ->when(
                ! empty($filters['category']),
                fn ($q) => $q->whereHas(
                    'category',
                    fn ($catQ) => $catQ->where('name', 'like', '%'.addcslashes($filters['category'], '%_').'%')
                )
            );

        $products = $query->orderBy($sortBy, $sortOrder)->paginate(perPage: $perPage, page: $page)->toArray();

        return PaginatedCollectionDTO::fromProducts($products['data'], $products['per_page'], $products['current_page'], $products['total']);
    }

    public function getProductById(int $id): ProductDTO
    {
        $product = Product::with('category')->findOrFail($id)->toArray();

        return ProductDTO::fromArray($product);
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
            throw new Exception('Failed to create product: '.$e->getMessage());
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
            throw new Exception('Failed to update product: '.$e->getMessage());
        }
    }

    public function updateProductQuantity(int $id, int $quantity): ProductDTO
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->quantity = $quantity;
            $product->save();

            DB::commit();

            return ProductDTO::fromArray($product->load('category')->toArray());
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update product: '.$e->getMessage());
        }
    }

    public function deleteProduct(int $id): bool
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to delete product: '.$e->getMessage());
        }
    }
}
