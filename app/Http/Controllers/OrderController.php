<?php

namespace App\Http\Controllers;

use App\Models\CartProduct;
use App\Models\Order;
use App\Models\WishlistProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $request->validate([
            'items' => ['array', 'nullable'],
            'from' => ['nullable', 'in:cart,wishlist'],
            'product_id' => ['nullable', 'integer'],
            'option_id' => ['nullable', 'integer'],
            'quantity' => ['nullable', 'integer'],
            'total_amount' => ['nullable', 'numeric'],
        ]);

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
            // Manual item (e.g. Buy Now)
            $cart = $request->user()->cart;

            $item = CartProduct::create([
                'cart_id' => $cart->id,
                'product_id' => $request->input('product_id'),
                'option_id' => $request->input('option_id'),
                'quantity' => $request->input('quantity', 1),
                'total_amount' => $request->input('total_amount', 0),
                'updated_at' => now(),
            ]);
            // Use Eloquent get() to return a collection of model(s)
            $items = CartProduct::where('id', $item->id)
                ->with(['product.images', 'option.images'])
                ->get();
        }


        return Inertia::render('shop/order/Create', [
            'items' => $items,
            'from' => $request->input('from'),
        ]);
    }



    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.note' => 'nullable|string|max:255',
            'from' => 'in:cart,wishlist|nullable',
        ]);


        $user = Auth::user();

        foreach ($request->items as $item) {
            $user->addOrder([
                'total_amount' => $item['total_amount'],
                'quantity' => $item['quantity'] ?? 12,
                'note' => $item['note'],
                'product_id' => $item['product_id'],
                'option_id' => $item['option_id'] ?? null,
            ]);

            if ($request->input('from') === 'wishlist')
                WishlistProduct::find($item['id'])->delete();
            else {
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

        sleep(1);
        $message = $request->input('status') == 'cancelled' ?
            'Order cancellation successfully!' :
            'Order received';

        return to_route('order.index')->with('status', [
            'type' => 'success',
            'message' =>  $message,
        ]);
    }

    public function destroy(Order $order)
    {
        $order->update([
            'status' => 'cancelled'
        ]);

        sleep(1);

        return to_route('order.index')->with('status', [
            'type' => 'success',
            'message' => 'Order cancellation successfully!',
        ]);
    }
}
