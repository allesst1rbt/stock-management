<?php

namespace App\DTOs;

class PaginatedCollectionDTO
{
    public function __construct(private array $items, private ?int $limit = 10, private ?int $page = 1, private ?int $total = 0) {}

    public static function fromProducts(array $products, ?int $limit, ?int $page, ?int $total): self
    {
        return new self(
            items: array_map(
                fn (array $product) => ProductDTO::fromArray($product),
                $products,
            ),
            limit: $limit,
            page: $page,
            total: $total
        );
    }

    public static function fromCategories(array $categories, ?int $limit, ?int $page, ?int $total): self
    {

        return new self(
            items: array_map(
                fn (array $category) => CategoryDTO::fromArray($category),
                $categories
            ),
            limit: $limit,
            page: $page,
            total: $total
        );
    }

    public function toArray(): array
    {
        return ['items' => array_map(
            fn ($item) => $item->toArray(),
            $this->items
        ),
            'limit' => $this->limit,
            'page' => $this->page,
            'total' => $this->total,
        ];
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
