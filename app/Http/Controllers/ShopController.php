<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        
        $categories = Category::pluck('name')->toArray();
        $user = $request?->user();
        $user?->loadMissing([
            'wishlist.items.products',
            'cart.items.products'
        ]);


        // dd($user->wishlist);
        return Inertia::render('shop/Index', [
            'categories' => $categories,
            'user' => $user,
            'filter' => $request->array('filter')
        ]);
    }

    public function show(Request $request, Product $product)
    {
        // dd($request->all());
        $product->load(['images', 'options.images', 'category', 'ratings.user', 'options.ratings', 'options.ratings.user']);
        return Inertia::render('shop/Show', [
            'product' => $product,
            'filter' => $request->array('filter')
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
}
