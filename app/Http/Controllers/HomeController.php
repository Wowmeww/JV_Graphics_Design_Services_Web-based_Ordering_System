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

        $contacts =  User::with(['sentMessages'])
            ->filter([
                'contacts' => true,
            ])
            ->get()->toArray();

        usort($contacts, function ($a, $b) {
            $i = end($a['sent_messages']) ? end($a['sent_messages'])['id'] : null;
            $j = end($b['sent_messages']) ? end($b['sent_messages'])['id'] : null;

            return  $j <=> $i; // desc
        });
        $tempContacts = array_map(function ($contact) use ($user) {
            // Filter messages to only include those sent to current user
            $contact['sent_messages'] = array_filter($contact['sent_messages'], function ($message) use ($contact, $user) {
                return $message['receiver_id'] === $user->id
                    && $message['sender_id'] === $contact['id'];
            });

            // Reset array keys if needed
            $contact['sent_messages'] = array_values($contact['sent_messages']);

            return $contact;
        }, $contacts);

        // Only load these if admin
        if ($user->is_admin) {
            $shop['orders'] = Order::with(['user', 'product.category', 'option'])
                ->latest()
                ->get();
            $shop['customers'] = User::where('role', 'customer')->count();
            $shop['products'] = Product::with('images')->get();
            $shop['announcements'] = Announcement::with('user')->latest()->get();
            $shop['messages'] = Message::where(function ($q) use ($user) {
                $q->where('seen', false)
                    ->where('receiver_id', $user->id);
            })->count();
        }
        $announcements = Announcement::with('user')->latest()->take(10)->get();

        return Inertia::render('Dashboard', [
            'user'         => $user,
            'shop'         => $shop,
            'announcements' => $announcements,
            'contacts' => $tempContacts,
        ]);
    }
}
