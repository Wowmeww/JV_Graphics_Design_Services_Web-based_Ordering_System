<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductOptionImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories first
        $categories = Category::factory(env('PRODUCT_CATEGORIES'))->create();

        // Create products
        Product::factory()
            ->count(env('PRODUCTS'))
            ->state(function () use ($categories) {
                return [
                    'type' => 'main product with variant',
                    'category_id' => $categories->random()->id,
                ];
            })
            ->has(
                ProductOption::factory()
                    ->count(rand(1, env('PRODUCTS_OPTIONS')))
                    ->has(ProductOptionImage::factory()->count(rand(1, 3)), 'images'),
                'options'
            )
            ->has(ProductImage::factory()->count(3), 'images')
            ->create();
    }
}

// PRODUCTS WITH OPTIONS
function productWithOptions()
{
    $tShirtCategory = Category::create(['name' => 'T-shirt printing']);
    $tarpaulinCategory = Category::create(['name' => 'Tarpaulin printing']);

    $tShirt = Product::factory()->create([
        'category_id' => $tShirtCategory->id,
        'name' => 'T-shirt with a dog picture',
        'description' => 'with a green dog picture',
        'price' => 140
    ]);
    ProductImage::factory()->create(['product_id' => $tShirt->id]);

    ProductOption::factory()->create([
        'product_id' => $tShirt->id,
        'name' => 'Size small'
    ]);
    ProductOption::factory()->create([
        'product_id' => $tShirt->id,
        'name' => 'Size large',
        'price' => 150
    ]);
    ProductOption::factory()->create([
        'product_id' => $tShirt->id,
        'name' => 'Size extra-large',
        'price' => 160
    ]);

    $tarpaulinPrice = 10; //pre square foot

    $tarpaulin = Product::factory()->create([
        'category_id' => $tarpaulinCategory->id,
        'name' => '3 x 7',
        'description' => '3 foot width 7 foot height',
        'price' => 3 * 7 * $tarpaulinPrice
    ]);
    ProductImage::factory()->create(['product_id' => $tarpaulin->id]);

    ProductOption::factory()->create([
        'product_id' => $tarpaulin->id,
        'name' => '4 x 9',
        'description' => '4 foot width 9 foot height',
        'price' => 4 * 9 * $tarpaulinPrice
    ]);
    ProductOption::factory()->create([
        'product_id' => $tarpaulin->id,
        'name' => '5 x 9',
        'description' => '5 foot width 9 foot height',
        'price' => 5 * 9 * $tarpaulinPrice
    ]);
}
