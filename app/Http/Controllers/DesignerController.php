<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DesignerController extends Controller
{
    public function index(Request $request, Product $product)
    {
        // Eager load relationships for the product
        $product->load([
            'images',
            'category',
            'options.images',
            'options.ratings.user',
            'ratings' => fn($q) => $q->with('user')->latest(), // newest product ratings
        ]);
        return Inertia::render('Designer', [
            'product' => $product
        ]);
    }
}
