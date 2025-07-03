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
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders->load([
            'product.images',
            'option.images',
        ])->sortByDesc('created_at')->values();

        return Inertia::render('shop/order/Index', [
            'user' => $user,
            'orders' =>  $orders
        ]);
    }
    public function create(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array']
        ]);
        $items = $request->input('from') === 'cart' ?
            CartProduct::find($request->input('items')) :
            WishlistProduct::find($request->input('items'));

        $items->load(['product.images', 'option.images']);
        return Inertia::render('shop/order/Create', [
            'items' => $items,
            'from' => $request->input('from')
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.note' => 'nullable|string|max:255',
            'from' => 'required|in:cart,wishlist',
        ]);


        $user = Auth::user();

        foreach ($request->items as $item) {
            $user->addOrder([
                'total_amount' => $item['total_amount'],
                'quantity' => $item['quantity'],
                'note' => $item['note'],
                'product_id' => $item['product_id'],
                'option_id' => $item['option_id'] ?? null,
            ]);

            if ($request->input('from') === 'cart')
                CartProduct::find($item['id'])->delete();
            else {
                // If from wishlist, delete the wishlist item
                WishlistProduct::find($item['id'])->delete();
            }
        }

        return to_route('dashboard')->with('status', [
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
