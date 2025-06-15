<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {

        $categories = Category::all();
        $products = [];

        foreach ($categories as $category) {
            $products[] = $category->products()->with(['images', 'category'])->first();
        }
        return Inertia::render('Welcome', [
            'products' => $products,
        ]);
    }

    public function dashboard(Request $request)
    {
        $products = Product::with(['images', 'category']);
        return Inertia::render('Dashboard', [
            'products'=> $products->paginate(10)
        ]);
    }
}
