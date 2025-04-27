<?php

namespace App\DTOs;

class CollectionDTO
{
    /**
     * @var array
     */
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public static function fromProducts(array $products): self
    {
        return new self(
            array_map(
                fn(array $product) => ProductDTO::fromArray($product),
                $products
            )
        );
    }

    public static function fromCategories(array $categories): self
    {
        return new self(
            array_map(
                fn(array $category) => CategoryDTO::fromArray($category),
                $categories
            )
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
