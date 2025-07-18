<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\Product;
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
            $items = [$item];
        }


        return Inertia::render('shop/order/Create', [
            'items' => $items,
            'from' => $request->input('from'),
        ]);
    }



    public function store(Request $request)
    {
        // dd($request->items);
        $request->validate([
            'items' => 'required|array',
            'items.*.note' => 'nullable|string|max:255',
            'from' => 'in:cart,wishlist|nullable',
        ]);

        $user = Auth::user();


        foreach ($request->items as $item) {
            $order = $user->addOrder([
                'total_amount' => $item['total_amount'],
                'quantity' => $item['quantity'] ?? 12,
                'note' => $item['note'],
                'product_id' => $item['product_id'],
                'option_id' => $item['option_id'] ?? null,
            ]);
            // I think the event is not dispatching in this line, no indicator in my log
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

        sleep(1);

        return to_route('order.index')->with('status', [
            'type' => 'success',
            'message' => 'Order cancellation successfully!',
        ]);
    }
}
