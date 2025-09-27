<?php

namespace App\Http\Controllers;

use App\Events\NewOrderNotificationEvent;
use App\Events\OrderPlaced;
use App\Events\TransactionEvent;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\User;
use App\Models\WishlistProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        $user = Auth::user();
        $orders = $user->orders->load([
            'product.images',
            'option.images',
            'rating'
        ])->sortByDesc('created_at')->values();

        return Inertia::render('shop/order/Index', [
            'user' => $user,
            'orders' =>  $orders,
            'tab' => $request->input('tab')
        ]);
    }
    public function create(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'items' => ['array', 'nullable'],
            'from' => ['nullable', 'in:cart,wishlist'],
            'item' => ['nullable', 'array'],
            'total_amount' => ['nullable', 'numeric'],
        ]);
        // 'item.product_id' => ['required', 'numeric'],
        //     'item.option_id' => ['nullable', 'numeric'],
        //     'item.quantity' => ['required', 'numeric', 'min:12', 'max:24'],
        //     'item.total_amount' => ['required', 'numeric'],

        $items = collect();

        if ($request->input('from') === 'cart') {
            $items = CartProduct::whereIn('id', $request->input('items', []))
                ->with(['product.images', 'option.images'])
                ->get();
        } elseif ($request->input('from') === 'wishlist') {
            $items = WishlistProduct::whereIn('id', $request->input('items', []))
                ->with(['product.images', 'option.images'])
                ->get();
        } else {
            $attributes = $request->input('item');
            $product = Product::with(['images'])->find($attributes['product_id']);
            $item = [
                'product' => $product,
                'product_id' => $attributes['product_id'],
                'option' => null,
                'option_id' => null,
                'quantity' => $attributes['quantity'],
                'total_amount' => $attributes['total_amount'],
            ];
            if ($attributes['option_id']) {
                $option = $product->options()->with(['images'])->where(['id' => $attributes['option_id']])->first();
                $item['option'] = $option;
                $item['option_id'] = $option->id;
            }
            if ($request->input('item') && isset($request->input('item')['images'])) {
                $item['images'] = $request->input('item')['images'];
            }
            $items = [$item];
        }
        return Inertia::render('shop/order/Create', [
            'items' => $items,
            'from' => $request->input('from'),
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        foreach ($request->input('items') as $item) {
            if (isset($item['images'])) {
                // dd($item['images']);
                foreach ($item['images'] as $image) {
                    $validator = Validator::make(['image' => $image['file']], [
                        'image' => [
                            'required',
                            'image',
                            'mimes:jpeg,png,jpg,gif,webp',
                            'max:3072', // 3MB max size
                        ],
                    ]);

                    if ($validator->fails()) {
                        return back()->with('status', [
                            'type' => 'error',
                            'message' =>  implode(',', $validator->errors()->get('image'))
                        ]);
                    }
                }
            };
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.note' => 'nullable|string|max:255',
            'from' => 'in:cart,wishlist|nullable',
            'type' => 'nullable'
        ]);

        $user = Auth::user();
        foreach ($request->items as $item) {
            $product = $item['option_id'] ? ProductOption::find($item['option_id']) : Product::find($item['product_id']);
            $order = $user->addOrder([
                'total_amount' => $product->price * ($item['quantity'] ?? 12),
                'quantity' => $item['quantity'] ?? 12,
                'note' => $item['note'],
                'product_id' => $item['product_id'],
                'option_id' => $item['option_id'] ?? null,
            ]);
            if (isset($item['type']) && $item['type'] === 'custom') {

                if ($request->input('from') === 'cart') {
                    $order->update(['type' => 'custom']);
                    CartProduct::find($item['id'])->resource()->each(function ($resource) use ($order) {
                        $resource->update([
                            'order_id' => $order->id,
                            'cart_product_id' => null
                        ]);
                    });
                }
            }

            $admins = User::where('role', 'admin')->get('id');

            foreach ($admins as $admin) {

                $notification = $admin->notifications()->create([
                    'from' => $user->id,
                    'receiver' => $admin->id,
                    'header' => 'Order Placed',
                    'content' => "Customer {$user->name} has placed a new order (#{$order->id}) with {$order->quantity} item(s), totaling ₱" . number_format($order->total_amount, 2) . ".",
                ]);

                // help this event is not despatching
                broadcast(new NewOrderNotificationEvent(User::find($admin->id), $notification));
            }
            event(new OrderPlaced($order));

            if ($request->input('from') === 'wishlist')
                WishlistProduct::find($item['id'])->delete();
            if ($request->input('from') === 'cart') {
                // If from wishlist, delete the wishlist item
                CartProduct::find($item['id'])->delete();
            }
        }

        return to_route('order.index')->with('status', [
            'type' => 'success',
            'message' => 'Order placed successfully!',
        ]);
    }

    public function show(Order $order)
    {

        Inertia::render('shop/order/Show');
    }


    public function edit(Order $order)
    {
        //

        return Inertia::render('shop/order/Edit');
    }

    public function update(Request $request, Order $order)
    {
        $order->update([
            'status' => $request->input('status')
        ]);

        $message = $request->input('status') == 'cancelled' ?
            'Order cancellation successfully!' :
            'Order received';

        if ($request->input('status') === 'received') {
            $admins = User::where('role', 'admin')->get();

            $admins->each(function ($admin) use ($order) {
                $from = request()->user();
                $notification = $admin->notifications()->create([
                    'from' => $from->id,
                    'receiver' => $admin->id,
                    'header' => 'Order Received Confirmed',
                    'content' => "{$from->name} has confirmed receipt of Order #{$order->id}, totaling ₱" . number_format($order->total_amount, 2) . " for {$order->quantity} item(s).",
                ]);

                event(new NewOrderNotificationEvent($admin, $notification));
            });

            $transaction = $order->user->transactions()->create([
                'order_id' => $order->id,
                'header' => 'Order Paid & Received',
                'content' => "Order #{$order->id} with {$order->quantity} item(s) has been paid and confirmed received. Total payment: ₱" . number_format($order->total_amount, 2) . ".",
            ]);

            event(new TransactionEvent($transaction));
        }
        return redirect()->route('order.index')->with('status', [
            'type' => 'success',
            'message' =>  $message,
        ]);
    }

    public function destroy(Order $order)
    {
        $order->update([
            'status' => 'cancelled'
        ]);

        return to_route('order.index')->with('status', [
            'type' => 'success',
            'message' => 'Order cancellation successfully!',
        ]);
    }
}
