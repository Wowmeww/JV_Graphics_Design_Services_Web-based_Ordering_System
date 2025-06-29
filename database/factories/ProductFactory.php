<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'category_id' => Category::factory(),
            'name' => fake()->realText(rand(10, 25)),
            'description' => fake()->realText(rand(40, 200)),
            'price' => rand(1, 5) * 89,
            'stock' => (rand(0, 5) * 8) * rand(1, 20),
            'size' => rand(1, 10) . "*" . rand(1, 20) . "*" . rand(1, 15) . "," . fake()->randomElement(['inc', 'cm', 'foot', 'miter']),
        ];
    }
}
