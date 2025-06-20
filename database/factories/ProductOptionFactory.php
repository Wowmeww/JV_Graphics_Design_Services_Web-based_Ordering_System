<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductOption>
 */
class ProductOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => fake()->realText(rand(10, 25)),
            'description' => fake()->realText(rand(14, 40)),
            'price' => (rand(1, 5) * 12) * 79,
            'stock' => (rand(1, 5) * 12) * 16,
            'size' => rand(1, 10) . "*" . rand(1, 20) . "*" . rand(1, 15) . "," . fake()->randomElement(['inc', 'cm', 'foot', 'miter']),
        ];
    }
}
