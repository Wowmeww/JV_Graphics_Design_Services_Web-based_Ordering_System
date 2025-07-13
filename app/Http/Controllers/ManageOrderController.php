<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\OrderStatusEvent;
use App\Events\Test;
use App\Models\User;

class ManageOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $orders = Order::query()
            ->with([
                'user:id,name,email',
                'product:id,name,slug', // Only select essential product fields
                'product.images:id,imageable_id,path',
                'option:id,name',
                'option.images:id,imageable_id,path'
            ])
            ->latest()
            ->filter($validated)
            ->paginate(8)
            ->withQueryString();

        return Inertia::render('manage/order/Index', [
            'orders' => $orders,
            'filters' => $validated,
            'statusOptions' => [
                'pending',
                'processing',
                'completed',
                'cancelled',
                'received',
                'rated',
            ]
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {

        $order->load(['product.images', 'option.images', 'user.orders']);
        return Inertia::render('manage/order/Show', [
            'order' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:255'],
        ]);

        $order->update([
            'status' => $validated['status']
        ]);

        // Optional: send a message if provided
        if (!empty($validated['message'])) {
            Message::create([
                'sender_id' => $request->user()->id,
                'receiver_id' => $order->user_id,
                'content' => $validated['message']
            ]);
        }

        event(new OrderStatusEvent(User::find($order->user_id)));

        return back()->with('status', [
            'type' => 'success',
            'message' => 'The order status has been updated to "' . ucfirst($validated['status']) . '"' .
                (!empty($validated['message']) ? ' and your message has been sent to the customer.' : '.')
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
