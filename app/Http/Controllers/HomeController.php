<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
}
