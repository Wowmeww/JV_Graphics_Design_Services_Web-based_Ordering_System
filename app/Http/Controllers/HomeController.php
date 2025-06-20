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

        // dd(Category::all());

        foreach ($categories as $category) {
            $products[] = $category->products()->with(['images', 'category'])->first();
        }

        // dd($products);
        return Inertia::render('Welcome', [
            'products'=> $products,
            'categories' => $categories
        ]);
    }

    public function dashboard(Request $request)
    {
        
    }
}
