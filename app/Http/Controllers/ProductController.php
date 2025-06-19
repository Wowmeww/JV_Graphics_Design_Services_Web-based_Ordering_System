<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with(['images', 'category'])
            ->filter(request(['search', 'category', 'stock', 'sort']))
            ->paginate(10)
            ->withQueryString();
        // return Inertia::render('product/Test', [
        //     'products' => $products,
        //     'categories' => Category::all(['id', 'name']),
        // ]);
        return Inertia::render('product/Index', [
            'products' => $products,
            'categories' => Category::all(['id', 'name']),
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('product/Show', [
            'product' => $product->load(['category', 'images']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
