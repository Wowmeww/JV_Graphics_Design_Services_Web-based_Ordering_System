<?php

namespace App\Http\Controllers;

use App\Models\CartProduct;
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
        // if ($product->type === 'custom') {
        //     return redirect()->route('designer', $product)->with('status', session('status'));
        // }
        // Eager load relationships for the product
        $product->load([
            'images',
            'category',
            'options.images',
            'options.ratings.user',
            'ratings' => fn($q) => $q->with('user')->latest(), // newest product ratings
        ]);

        // Eager load relationships for the selected option if provided
        if ($option) {
            $option->load([
                'images',
                'ratings' => fn($q) => $q->with('user')->latest(), // newest option ratings
            ]);
        }

        return Inertia::render('shop/Show', [
            'product' => $product,
            'option' => $option,
            'filter' => $request->array('filter'),
            'quantity' => $request->input('quantity', 12),
        ]);
    }



    public function fetch(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $products = Product::with(['images', 'options.images', 'category'])
        ->whereNot('type', 'unavailable')
            ->filter(request(['search', 'category', 'stock', 'sort', 'type']))
            ->paginate($perPage);
        return response()->json($products);
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
