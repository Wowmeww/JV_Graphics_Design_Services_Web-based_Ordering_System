<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {

        $products = Category::with(['products.images', 'products.category'])
            ->get()
            ->flatMap(function (Category $category) {
                return $category->products->sortByDesc('created_at')->take(2);
            })
            ->filter()
            ->values();

        return Inertia::render('Welcome', [
            'products' => $products,
        ]);
    }

    public function about()
    {
        return Inertia::render('About');
    }
    public function privacy()
    {

        return Inertia::render('Privacy');
    }
}
