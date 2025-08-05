<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->orderBy('id', 'desc')->get();
        $notifications->load('from');
        return Inertia::render('auth/notifications/Notifications', [
            'notifications' => $notifications
        ]);
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'seen' => ['nullable', 'boolean']
        ]);
        $notification->update(['seen' => $request->input('seen')]);

        return redirect()->route('notification.index')->with('status', [
            'type' => 'success',
            'message' => 'Notification updated to "' . ($request->input('seen') ? 'read' : 'unread') . '".',
        ]);
    }
    public function show(Notification $notification)
    {
        //
    }
    public function markAllRead(Request $request)
    {
        $request->user()->notifications()->update(['seen' => true]);

        return redirect()->back()->with('status', [
            'type' => 'success',
            'message' => 'All notifications marked as read'
        ]);
    }
}
