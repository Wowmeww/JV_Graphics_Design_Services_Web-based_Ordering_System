<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'admin_id' => User::where('role', 'admin')->inRandomOrder()->value('id'),
            'title' => fake()->realText(rand(16, 30)),
            'content' => implode("\n\n", fake()->paragraphs(rand(1, 7))),
        ];
    }
}
