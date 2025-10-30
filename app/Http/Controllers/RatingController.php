<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $fields = $request->validate([
            'stars' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'message' => ['nullable', 'string'],
        ]);

        $order->update(['status' => 'rated']);
        $order->rating()->create([
            ...$fields,
            'user_id' => $order->user_id,
            'product_id' => $order->product_id,
            'option_id' => $order->option_id,
        ]);
        // Under Review
        // Processing
        // Completed
        // Canceled
        // Rate

        return to_route('order.index', [
            'tab' => 'Rate'
        ])->with('status', [
            'type' => 'success',
            'message' => 'Rating and feedback posted',
        ]);
    }

    public function update(Request $request, Order $order)
    {

        $fields = $request->validate([
            'stars' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'message' => ['nullable', 'string'],
        ]);
        if (
            $order?->rating &&
            Carbon::parse($order->rating->created_at)
            ->addDay()
            ->isPast()
        ) {
            return to_route('order.index', [
                'tab' => 'Rate'
            ])->with('status', [
                'type' => 'warning',
                'message' => 'You can no longer update this rating — the 24-hour update period has expired.',
            ]);
        }

        $order->rating()->update($fields);

        return to_route('order.index', [
            'tab' => 'Rate'
        ])->with('status', [
            'type' => 'success',
            'message' => 'Rating and feedback updated & posted',
        ]);
    }
}
