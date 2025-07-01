<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartWishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carts = Cart::all();
        $wishlists = Wishlist::all();

        foreach ($carts as $cart) {
            for ($i = 0; $i < env('CART_ITEMS', 5); $i++) {
                $product = Product::inRandomOrder()->first();
                $qty = rand(12, 24);
                if (!$product) continue;

                if ($i % 2 === 0) {
                    // Product with option
                    $option = $product->options()->inRandomOrder()->first();

                    if ($option) {

                        $cart->products()->attach($product->id, [
                            'option_id' => $option->id,
                            'quantity' => $qty,
                            'total_amount' => $qty * $option->price,
                        ]);
                    }
                } else {
                    // Product without option

                    $cart->products()->attach($product->id, [
                        'quantity' => $qty,
                        'total_amount' => $qty * $product->price,
                    ]);
                }
            }
        }
        foreach ($wishlists as $wishlist) {
            for ($i = 0; $i < env('CART_ITEMS', 5); $i++) {
                $product = Product::inRandomOrder()->first();
                $qty = rand(12, 24);
                if (!$product) continue;

                if ($i % 2 === 0) {
                    // Product with option
                    $option = $product->options()->inRandomOrder()->first();

                    if ($option) {
                        $wishlist->products()->attach($product->id, [
                            'option_id' => $option->id,
                            'quantity' => $qty,
                            'total_amount' => $qty * $option->price,
                        ]);
                    }
                } else {
                    // Product without option
                    $wishlist->products()->attach($product->id, [
                        'quantity' => $qty,
                        'total_amount' => $qty * $product->price,
                    ]);
                }
            }
        }
    }
}
