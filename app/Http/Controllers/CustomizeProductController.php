<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSampleElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CustomizeProductController extends Controller
{
    public function create()
    {
        return Inertia::render('product/custom/Create');
    }
    public function edit(Product $product)
    {
        $product->load(['images', 'category', 'designs']);
        [$product->size, $product->unit] = $this->splitSize($product->size);
        return Inertia::render('product/custom/Edit', [
            'product' => $product
        ]);
    }

    public function destroyDesign(ProductSampleElement $design)
    {
        Storage::disk('public')->delete($design->image);
        $design->delete();

        return redirect()->back()->with(
            'status',
            [
                'type' => 'success',
                'message' => 'Deletion successful'
            ]
        );
    }

    private function splitSize(?string $size): array
    {
        $assocSize = json_decode($size);
        return [$assocSize->size, $assocSize->unit];
    }
}
