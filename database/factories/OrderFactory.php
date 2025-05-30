<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = rand(1,4) * 12;
        return [
            'user_id' => User::factory(),
            'status' => 'pending',
            'type' => 'normal',
            'quantity' => $qty,
            'total_amount' => $qty * 68,
            'note' => fake()->randomElement([null, fake()->realText(rand(16, 30))])
        ];
    }
}
