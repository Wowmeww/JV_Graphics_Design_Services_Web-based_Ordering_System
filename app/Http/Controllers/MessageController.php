<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ?User $receiver = null)
    {
        // how do I sort the contacts?
        $user = $request->user();
        $contacts = [];

        if ($user->is_admin) {
            $contacts = User::where('id', '!=', $user->id);
        } else {
            $contacts = User::where('role', 'admin');
            if ($receiver?->role !== 'admin' && $receiver) {
                abort(403);
            }
        }

        if ($receiver) {
            $messages = Message::with(['sender'])
                ->where(function ($q) use ($user, $receiver) {
                    $q->where('sender_id', $user->id)
                        ->where('receiver_id', $receiver->id);
                })
                ->orWhere(function ($q) use ($user, $receiver) {
                    $q->where('sender_id', $receiver->id)
                        ->where('receiver_id', $user->id);
                });

            if($request->boolean('all')) {
                $messages = $messages->get();
            } else {
                $messages = $messages->latest('id')
                    ->take(20)
                    ->get()
                    ->sortBy('id')
                    ->values();
            }
        } else {
            $messages = collect();
        }



        return Inertia::render('auth/message/Messages', [
            'contacts' => $contacts->get(),
            'receiver' => $receiver,
            'messages' => $messages,
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $receiver)
    {
        $sender = $request->user();
        $request->validate([
            'content' => ['string', 'required']
        ]);


        $message = $sender->sendMessage($receiver->id, $request->input('content'));

        broadcast(new MessageSent($receiver, $message));

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
