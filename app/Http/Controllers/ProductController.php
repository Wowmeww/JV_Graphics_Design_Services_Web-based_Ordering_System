<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
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
    public function show(Product $product, ?ProductOption $option = null)
    {
        $product->load(['category', 'options', 'images', 'options.images']);
        [$product->size, $product->unit] = $this->splitSize($product->size);
        if ($option?->id) {
            $option->load(['images']);
            [$option->size, $option->unit] = $this->splitSize($option->size);
        }
        // dump($option);
        return Inertia::render('product/Show', [
            'product' => $product,
            'option' => $option,
        ]);
    }

    private function splitSize(?string $size): array
    {
        if ($size && str_contains($size, ',')) {
            return explode(',', $size, 2);
        }

        return [$size, null];
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, ?ProductOption $option = null)
    {
        $product->load(['category', 'options', 'images', 'options.images']);
        [$product->size, $product->unit] = $this->splitSize($product->size);
        if ($option?->id) {
            $option->load(['images']);
            [$option->size, $option->unit] = $this->splitSize($option->size);
        }
        // dump($option);
        return Inertia::render('product/Edit', [
            'product' => $product,
            'option' => $option,
        ]);
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
