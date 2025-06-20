<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
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
            for ($i = 0; $i < env('CART_ITEMS'); $i++) {
                $cartItem = CartItem::factory()->create([
                    'cart_id' => $cart->id
                ]);

                // Attach random product(s)
                $cartItem->products()->attach(rand(1, env('PRODUCTS')));
            }
        }

        foreach ($wishlists as $wishlist) {
            for ($i = 0; $i < env('WISHLIST_ITEMS'); $i++) {
                $wishlistItem = WishlistItem::factory()->create([
                    'wishlist_id' => $wishlist->id
                ]);

                // Attach random product(s)
                $wishlistItem->products()->attach(rand(1, env('PRODUCTS')));
            }
        }
    }
}
