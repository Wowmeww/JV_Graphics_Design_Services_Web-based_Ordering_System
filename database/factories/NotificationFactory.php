<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from' => User::factory(),
            'receiver' => User::factory(),
            'type' => 'primary',
            'header' => fake()->realText(rand(16, 34)),
            'content' => fake()->realText(rand(34, 100)),
            'seen' => false
        ];
    }
}
