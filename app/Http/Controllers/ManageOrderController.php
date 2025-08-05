<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\NewOrderNotificationEvent;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\OrderStatusEvent;
use App\Events\Test;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use ZipArchive;

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

        // ->whereNotIn('status', ['received', 'rated', 'cancelled'])
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
    public function show(Request $request, Order $order)
    {
        $order->load(['product.images', 'option.images', 'user.orders', 'resource']);
        return Inertia::render('manage/order/Show', [
            'order' => $order,
            'searches' =>  $request->input('searches')
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

        if ($validated['status'] === 'completed') {
            $newStock = $order->option ?
                $order->option->stock - $order->quantity :
                $order->product->stock - $order->quantity;

            if ($newStock < 1) {
                return back()->with('status', [
                    'type' => 'error',
                    'message' => 'No stocks for this product'
                ]);
            }

            if ($order->option) {
                $order->option()->update([
                    'stock' => $newStock
                ]);
            } else {
                $order->product()->update([
                    'stock' => $newStock
                ]);
            }
        }

        $order->update([
            'status' => $validated['status']
        ]);

        // Optional: send a message if provided
        if (!empty($validated['message'])) {
            $message = Message::create([
                'sender_id' => $request->user()->id,
                'receiver_id' => $order->user_id,
                'content' => $validated['message']
            ]);
            // dd($message->receiver);
            broadcast(new MessageSent(User::find($order->user_id), $message));
        }

        event(new OrderStatusEvent(User::find($order->user_id), $order));
        $user = User::find($order->user_id);
        $notification = $user->notifications()->create([
            'from' => $request->user()->id,
            'receiver' => $user->id,
            // make a better message
            'header' => 'Order Status Update',
            'content' => "Order #{$order->id} (₱" . number_format($order->total_amount, 2) . " for {$order->quantity} item(s)) has been updated to \"{$order->status}\" status.",
        ]);
        event(new NewOrderNotificationEvent($user, $notification));

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

    public function downloadResource(Request $request, Order $order)
    {
        $images = $order->resource->pluck('image');

        if ($images->isEmpty()) {
            return abort(400, 'No images specified');
        }

        // Create temp directory if needed
        $tempPath = storage_path('app/temp');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        // Optional: cleanup old ZIPs
        foreach (glob($tempPath . '/*.zip') as $oldZip) {
            if (filemtime($oldZip) < time() - 86400) {
                unlink($oldZip);
            }
        }

        $zipFileName = 'order_' . $order->id . '_images_' . now()->timestamp . '.zip';
        $zipPath = $tempPath . '/' . $zipFileName;

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($images as $imageName) {
                $filePath = storage_path('app/public/product_images/order_resource/' . basename($imageName));
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($imageName));
                } else {
                    dd($filePath);
                }
            }
            $zip->close();
        } else {
            return abort(500, 'Could not create ZIP file');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
