<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $products = Category::with(['products.images', 'products.category'])
            ->get()
            ->map(function (Category $category) {
                return $category->products->first();
            })
            ->filter(); // removes nulls automatically
        // dd($products);
        return Inertia::render('Welcome', [
            'products' => $products,
        ]);
    }



    public function dashboard(Request $request)
    {

        $user = $request->user();
        // $user->load(['messages']);

        return Inertia::render('Dashboard', [
            'user' => $user,
            'announcements' => Announcement::all()
        ]);
    }
}
