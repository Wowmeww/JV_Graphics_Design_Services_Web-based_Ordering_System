<?php

namespace Database\Factories;

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
            'name' => fake()->realText(rand(10, 25)),
            'description' => fake()->realText(rand(14, 40)),
            'price' => (rand(1, 5) * 12) * 79,
            'stock' => (rand(1, 5) * 12) * 16,
        ];
    }
}
