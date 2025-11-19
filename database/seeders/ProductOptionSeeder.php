<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product::all()->map(function (Product $product) {
        //     $product->options->each(function (ProductOption $option) use($product) {
        //         $option->update([
        //             'name' => "{$product->name} - {$option->name}"
        //         ]);
        //     });
        // });
    }
}
