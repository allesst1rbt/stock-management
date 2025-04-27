<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly int $stock_quantity,
        public readonly int $category_id,
        public readonly ?CategoryDTO $category = null,
        public readonly string $sku,
        public readonly bool $active = true,
        public readonly ?int $id = null,
        public readonly ?string $created_at = null,
        public readonly ?string $updated_at = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'],
            price: $data['price'],
            stock_quantity: $data['stock_quantity'],
            category_id: $data['category_id'],
            category: isset($data['category']) ? CategoryDTO::fromArray($data['category']) : null,
            sku: $data['sku'],
            id: $data['id'] ?? null,
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'category_id' => $this->category_id,
            'sku' => $this->sku,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ], fn($value) => !is_null($value));

        if ($this->category) {
            $data['category'] = $this->category->toArray();
        }

        return $data;
    }
}
