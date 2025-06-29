<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerUsers = User::where('role', 'customer')->get();

        if ($customerUsers->isEmpty()) {
            throw new \Exception("No users with the 'customer' role found.");
        }

        $products = Product::with('ratings')->get();
        $productOptions = ProductOption::with('product')->get();

        $ratingsPerProduct = env('RATING_PER_PRODUCT', 1); // fallback to 1 if not set
        $ratingsPerOption = env('RATING_PER_OPTION', 1);

        // Ratings for products (without option_id)
        foreach ($products as $product) {
            for ($i = 0; $i < $ratingsPerProduct; $i++) {
                $product->ratings()->create(
                    Rating::factory()->make([
                        'user_id' => $customerUsers->random()->id,
                        'option_id' => null, // explicitly null
                    ])->toArray()
                );
            }
        }

        // Ratings for product options (with option_id)
        foreach ($productOptions as $option) {
            for ($i = 0; $i < $ratingsPerOption; $i++) {
                Rating::factory()->create([
                    'product_id' => $option->product_id,
                    'option_id' => $option->id,
                    'user_id' => $customerUsers->random()->id,
                ]);
            }
        }
    }
}
