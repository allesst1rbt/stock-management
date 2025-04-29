<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(1, true),
            'description' => $this->faker->paragraph(1),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'quantity' => $this->faker->numberBetween(0, 100),
            'category_id' => Category::factory()->create()->id,
            'sku' => $this->generateSku(),

        ];

    }

    protected function generateSku(): string
    {
        return strtoupper(Str::random(2).$this->faker->numberBetween(1000, 9999));
    }
}
