<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Wishlist;
use App\Models\WishlistProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function edit(Request $request, WishlistProduct $wishlistItem)
    {
        $wishlistItem->load(['product.images', 'option.images', 'product.ratings.user',  'option.ratings.user', 'product.category']);

        return Inertia::render('shop/WishlistEdit', [
            'wishlistItem' => $wishlistItem
        ]);
    }

    public function update(Request $request,  WishlistProduct $wishlistItem)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:12', 'max:24'],
        ]);

        $wishlistItem->update(['quantity' => $validated['quantity']]);

        return redirect()->route('shop.show', [
            'product' => $wishlistItem->product->id,
            'option' => $wishlistItem->option?->id || null,
        ])->with([
            'status' => [
                'type' => 'success',
                'message' => 'Wishlist item updated successfully.'
            ],
            'isCartOpen' => false,
            'isWishlistOpen' => true,
            'shopAsideOpen' => true
        ]);
    }

    public function store(Request $request, Product $product, ?ProductOption $option = null)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:12', 'max:24'],
        ]);

        $user = $request->user();
        $wishlist = $user->wishlist;

        if (! $wishlist) {
            // Optionally create a cart if missing
            $wishlist = $user->wishlist()->create(); // or Cart::create(['user_id' => $user->id])
        }

        $wishlist->addItem($product, $option, $validated['quantity']);

        return redirect()->back()->with([
            'status' => [
                'type' => 'success',
                'message' => 'Added to wishlist successfully.'
            ],
            'isCartOpen' => false,
            'isWishlistOpen' => true,
            'shopAsideOpen' => true
        ]);
    }
    public function destroy(Request $request, ?WishlistProduct $wishlistItem = null)
    {
        $with = [
            'status' => [
                'type' => 'success',
                'message' => 'Item removed from wishlist.',
            ],
            'isCartOpen' => false,
            'isWishlistOpen' => true,
            'shopAsideOpen' => true,
        ];

        if ($request->filled('ids')) {
            WishlistProduct::whereIn('id', $request->input('ids'))->delete();
            $with['status']['message'] = 'Selected items removed from wishlist.';
        } elseif ($wishlistItem) {
            $wishlistItem->delete();
        } else {
            // Handle the case when nothing was deleted
            return redirect()->back()->with([
                'status' => [
                    'type' => 'error',
                    'message' => 'No item selected for deletion.',
                ],
            ]);
        }

        // return redirect()->to(url()->previous() ?? route('shop.index'))->with($with);

        return $request->input('from') === 'edit'
            ? redirect()->route('shop.index')->with($with)
            : redirect()->route('dashboard')->with($with);
    }
}
