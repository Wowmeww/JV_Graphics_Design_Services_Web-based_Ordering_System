<?php

namespace Database\Factories;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WishlistItem>
 */
class WishlistItemFactory extends Factory
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
            'wishlist_id' => Wishlist::factory(),
            'type' => 'normal',
            'quantity' => $qty,
            'total_amount' => $qty * 99
        ];
    }
}
