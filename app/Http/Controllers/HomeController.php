<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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

        // Load related models for the logged-in user
        if (! $user->is_admin) {
            $user->load([
                'cart',
                'wishlist',
                'orders.product.images',
                'orders.option.images'
            ]);
        }

        $shop = [
            'orders'    => [],
            'customers' => 0,
            'products'  => [],
            'announcements' => []
        ];

        // Only load these if admin
        if ($user->is_admin) {
            $shop['orders'] = Order::with(['user', 'product.category', 'option'])
                ->latest()
                ->get();
            $shop['customers'] = User::where('role', 'customer')->count();
            $shop['products'] = Product::with('images')->get();
            $shop['announcements'] = Announcement::with('user')->latest()->get();
            $shop['messages'] = Message::all()->count();
        }

        $announcements = Announcement::with('user')->latest()->take(10)->get();

        return Inertia::render('Dashboard', [
            'user'         => $user,
            'shop'         => $shop,
            'announcements' => $announcements,
        ]);
    }
}
