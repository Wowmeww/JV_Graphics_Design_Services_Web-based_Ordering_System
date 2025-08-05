<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomizeProductController extends Controller
{
    public function create()
    {
        return Inertia::render('product/custom/Create');
    }
    public function edit(Product $product)
    {
        $product->load(['images', 'category']);
        [$product->size, $product->unit] = $this->splitSize($product->size);
        return Inertia::render('product/custom/Edit', [
            'product' => $product
        ]);
    }

    private function splitSize(?string $size): array
    {
        if ($size && str_contains($size, ',')) {
            return explode(',', $size, 2);
        }

        return [$size, null];
    }
}
