<?php

namespace App\DTOs;

class CategoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?int $id = null,
        public readonly ?string $created_at = null,
        public readonly ?string $updated_at = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            id: $data['id'] ?? null,
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ], fn ($value) => ! is_null($value));
    }
    
}
