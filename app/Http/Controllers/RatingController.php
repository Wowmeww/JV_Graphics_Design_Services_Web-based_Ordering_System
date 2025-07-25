<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $user = $request->user();

        $fields = $request->validate([
            'stars' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'message' => ['nullable', 'string'],
        ]);

        $match = [
            'user_id'    => $user->id,
            'product_id' => $order->product_id,
            'option_id'  => $order->option_id ?? null,
        ];

        $data = [
            'stars'  => $fields['stars'],
            'message' => $fields['message'],
            'order_id' => $order->id
        ];

        $rating = Rating::updateOrCreate($match, $data);
        $order->update([
            'status' => 'rated',
            'rating_id' => $rating->id
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
}
