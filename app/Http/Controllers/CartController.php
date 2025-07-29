<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function edit(Request $request, CartProduct $cartItem)
    {
        $cartItem->load(['product.images', 'option.images', 'product.ratings.user',  'option.ratings.user', 'product.category']);

        return Inertia::render('shop/CartEdit', [
            'cartItem' => $cartItem
        ]);
    }

    public function update(Request $request, CartProduct $cartItem)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:12', 'max:24'],
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);

        return redirect()->route('shop.show', [
            'product' => $cartItem->product->id,
            'option' => $cartItem->option?->id || null,
        ])->with([
            'status' => [
                'type' => 'success',
                'message' => 'Cart item updated successfully.'
            ],
            'isCartOpen' => true,
            'isWishlistOpen' => false,
            'shopAsideOpen' => true
        ]);
    }


    public function store(Request $request, Product $product, ?ProductOption $option = null)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:12', 'max:24'],
            'type' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*.label' => ['required', 'string'],
            'images.*.file' => ['required', 'image'],
        ]);


        $user = $request->user();
        $cart = $user->cart;;
        if (! $cart) {
            // Optionally create a cart if missing
            $cart = $user->cart()->create(); // or Cart::create(['user_id' => $user->id])
        }
        if ($validated['type'] === 'custom') {
            if (count($request->files)) {
                dd($request->array('images'));
            }
        }

        dd('test');

        $cart->addItem($product, $option, $validated['quantity']);

        return redirect()->back()->with([
            'status' => [
                'type' => 'success',
                'message' => 'Added to cart successfully.'
            ],
            'isCartOpen' => true,
            'isWishlistOpen' => false,
            'shopAsideOpen' => true
        ]);
    }
    public function destroy(Request $request, ?CartProduct $cartItem = null)
    {
        $with = [
            'isCartOpen' => true,
            'isWishlistOpen' => false,
            'shopAsideOpen' => true,
        ];

        // Bulk deletion via array of IDs from request
        if ($request->filled('ids')) {
            CartProduct::whereIn('id', $request->input('ids'))->delete();
            $with['status'] = [
                'type' => 'success',
                'message' => 'Selected items removed from cart.',
            ];
        }
        // Single deletion via route model binding
        elseif ($cartItem) {
            $cartItem->delete();
            $with['status'] = [
                'type' => 'success',
                'message' => 'Item removed from cart.',
            ];
        }
        // Nothing was deleted
        else {
            $with['status'] = [
                'type' => 'error',
                'message' => 'No item selected for deletion.',
            ];
        }

        return $request->input('from') === 'edit'
            ? redirect()->route('shop.index')->with($with)
            : redirect()->route('dashboard')->with($with);
    }
}
