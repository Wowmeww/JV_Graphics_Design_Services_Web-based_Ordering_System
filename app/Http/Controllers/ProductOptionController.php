<?php

namespace App\Http\Controllers;

use App\Models\ProductOption;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductOptionController extends Controller
{

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
    public function show(ProductOption $option)
    {
        $option->load(['product', 'product.category', 'images', 'product.images']);
        [$option->size, $option->unit] = $this->splitSize($option->size);
        return Inertia::render('product/option/Show', [
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
    public function edit(ProductOption $option)
    {
        $option->load(['product', 'product.category', 'images', 'product.images']);
        [$option->size, $option->unit] = $this->splitSize($option->size);
        if ($option?->id) {
            $option->load(['images']);
            [$option->size, $option->unit] = $this->splitSize($option->size);
        }
        return Inertia::render('product/option/Edit', [
            'option' => $option,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductOption $option)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductOption $option)
    {
        //
    }
}
