<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {

        $categories = Category::pluck('name')->toArray();
        $user = $request?->user();
        $user?->loadMissing([
            'wishlist.products',
            'cart.products'
        ]);
        return Inertia::render('shop/Index', [
            'categories' => $categories,
            'user' => $user,
            'filter' => $request->array('filter')
        ]);
    }

    public function show(Request $request, Product $product, ?ProductOption $option = null)
    {
        // dd($request->all());
        $product->load(['images', 'options.images', 'category', 'ratings.user', 'options.ratings', 'options.ratings.user']);
        $option?->load(['images', 'ratings.user']);
        return Inertia::render('shop/Show', [
            'product' => $product,
            'option' => $option,
            'filter' => $request->array('filter'),
            'quantity' => $request->input('quantity', 12)
        ]);
    }

    public function fetch(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $products = Product::with(['images', 'options.images'])
            ->filter(request(['search', 'category', 'stock', 'sort', 'type']))
            ->paginate($perPage);
        return response()->json($products);
    }

    public function addToCart(Request $request, Product $product, ?ProductOption $option = null)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:12', 'max:24'],
        ]);

        $user = $request->user();
        $cart = $user->cart;

        if (! $cart) {
            // Optionally create a cart if missing
            $cart = $user->cart()->create(); // or Cart::create(['user_id' => $user->id])
        }

        $cart->addItem($product, $option, $validated['quantity']);

        return redirect()->back()->with([
            'status' => [
                'type' => 'success',
                'message' => 'Added to cart successfully.'
            ],
            'isCartOpen' => true,
            'shopAsideOpen' => true
        ]);
    }
    public function deleteCartItem(Request $request, Product $product, ?ProductOption $option = null)
    {
        $user = $request->user();
        $cart = $user->cart;
        $cart->deleteItem($product, $option);

        return redirect()->back()->with([
            'status' => [
                'type' => 'success',
                'message' => 'Removed from.',
            ],
            'isCartOpen' => true,
            'shopAsideOpen' => true
        ]);
    }

    public function addToWishlist(Request $request, Product $product, ?ProductOption $option = null)
    {
        dd('wishlist');
    }
    public function addToOrder(Request $request, Product $product, ?ProductOption $option = null)
    {
        dd('order');
    }
}
