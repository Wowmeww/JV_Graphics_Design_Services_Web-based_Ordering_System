<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductOptionImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryCount = (int) env('PRODUCT_CATEGORIES', 5);
        $productCount = (int) env('PRODUCTS', 10);
        $optionCount = (int) env('PRODUCTS_OPTIONS', 3);

        // Create categories and store IDs only (faster memory-wise)
        $categoryIds = Category::factory($categoryCount)->create()->pluck('id');

        // Create products with relationships using bulk creation
        Product::factory()
            ->count($productCount)
            ->state(fn() => [
                'type' => 'Main product with variant',
                'category_id' => $categoryIds->random(),
            ])
            ->has(
                ProductOption::factory()
                    ->count($optionCount)
                    ->has(
                        ProductOptionImage::factory()->count(2),
                        'images'
                    ),
                'options'
            )
            ->has(ProductImage::factory()->count(2), 'images')
            ->create();
    }
}
