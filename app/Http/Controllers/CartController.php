<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    function store(Request $request, Product $product, ?ProductOption $option = null)
    {
        // dd($request->images);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:12', 'max:24'],
            'type' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*.label' => ['required', 'string'],
            'images.*.file' => ['required', 'file', 'max:2048'], // when i remove the mimes its working
        ]);
        $validated['type'] = isset($validated['type']) ? $validated['type'] : 'normal';

        $user = $request->user();
        $cart = $user->cart;;
        if (! $cart) {
            // Optionally create a cart if missing
            $cart = $user->cart()->create(); // or Cart::create(['user_id' => $user->id])
        }

        $cartItem = $cart->addItem($product, $option, $validated['quantity']);

        if (($validated['type'] === 'custom')) {
            $cartItem->update(['type' => 'custom']);
            // dd($cartItem);
            if (count($request->files)) {
                foreach ($validated['images'] as $image) {
                    $image_path = Storage::disk('public')->put('/product_images/order_resource', $image['file']);
                    // dd($cartItem->resource());
                    $cartItem->resource()->create([
                        'label' => $image['label'],
                        'image' => $image_path
                    ]);
                }
            }
        }

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
            $cartItems = CartProduct::whereIn('id', $request->input('ids'))->get();

            foreach ($cartItems as $item) {
                foreach ($item->resource as $resource) {
                    if ($resource->image && Storage::disk('public')->exists($resource->image)) {
                        Storage::disk('public')->delete($resource->image);
                    }

                    $resource->delete();
                }
                $item->delete();
            }

            $with['status'] = [
                'type' => 'success',
                'message' => 'Selected items removed from cart.',
            ];
        }

        // Single deletion via route model binding
        elseif ($cartItem) {
            foreach ($cartItem->resource as $resource) {
                if ($resource->image && Storage::disk('public')->exists($resource->image)) {
                    Storage::disk('public')->delete($resource->image);
                }

                $resource->delete();
            }
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
