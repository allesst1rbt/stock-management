<?php

namespace App\DTOs;

class PaginatedCollectionDTO
{



    public function __construct(private array $items, private ?int $limit = 10, private ?int $page = 1)
    {}

    public static function fromProducts(array $products, ?int $limit, ?int $page): self
    {
        return new self(
            items: array_map(
                fn(array $product) => ProductDTO::fromArray($product),
                $products,
            ),
            limit: $limit,
            page: $page
        );
    }

    public static function fromCategories(array $categories, ?int $limit, ?int $page): self
    {
        return new self(
            items: array_map(
                fn(array $category) => CategoryDTO::fromArray($category),
                $categories
            ),
            limit: $limit,
            page: $page
        );
    }

    public function toArray(): array
    {
        return array_map(
            fn($item) => $item->toArray(),
            $this->items
        );
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
