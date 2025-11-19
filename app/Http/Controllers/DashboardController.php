<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $user->load(['receivedMessages']);

        if (! $user->is_admin) {
            $user->load([
                'cart',
                'wishlist',
                'orders.product.images',
                'orders.option.images'
            ]);
        }

        $shop = $this->loadShopData($user);
        $contacts = $this->loadContacts($user);
        $announcements = Announcement::with('user')->latest()->take(10)->get();

        // dd($shop['products']);
        if ($user->is_admin) {
            return Inertia::render('auth/dashboard/Admin', [
                'contacts' => $contacts,
                'announcements' => $announcements,
                'admin' => $user,
                'shop' => $shop
            ]);
        }

        return Inertia::render('auth/dashboard/Customer', [
            'user' => $user,
            'shop' => $shop,
            'contacts' => $contacts,
            'announcements' => $announcements,
        ]);
    }

    private function loadContacts(User $user)
    {
        $contacts = User::with(['sentMessages'])
            ->filter(['contacts' => true])
            ->get()
            ->toArray();

        usort($contacts, function ($a, $b) {
            $i = end($a['sent_messages'])['id'] ?? null;
            $j = end($b['sent_messages'])['id'] ?? null;
            return $j <=> $i;
        });

        return array_map(function ($contact) use ($user) {
            $contact['sent_messages'] = array_values(array_filter(
                $contact['sent_messages'],
                fn($msg) => $msg['receiver_id'] === $user->id && $msg['sender_id'] === $contact['id']
            ));
            return $contact;
        }, $contacts);
    }

    private function loadShopData(User $user)
    {
        if (! $user->is_admin) {
            return [
                'orders' => [],
                'customers' => 0,
                'products' => [],
                'announcements' => [],
                'visitors' => [],
                'revenue' => 0
            ];
        }
        $products = [
            ...Product::with('images')->get()->where(fn($b) => $b->rating)->toArray(),
            ...ProductOption::with('images')->get()->where(fn($b) => $b->rating)->toArray()
        ];
        return [
            'orders' => Order::with(['user', 'product.category', 'option'])->latest()->get(),
            'customers' => User::where('role', 'customer')->count(),
            'products' => $products,
            'announcements' => Announcement::with('user')->latest()->get(),
            'messages' => Message::where('seen', false)->where('receiver_id', $user->id)->count(),
            'visitors' => json_decode(SystemSetting::where('key', 'daily_visitors')->value('value'), true),
            'revenue' => Order::whereIn('status', ['rated', 'received'])->sum('total_amount'),
        ];
    }
}
