<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function PHPSTORM_META\type;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ?User $receiver = null)
    {
        $user = $request->user();
        $contacts =  User::with(['sentMessages'])
            ->filter([
                'contacts' => true,
                ...request(['search'])
            ])
            ->get()->toArray();
        usort($contacts, function ($a, $b) {

            $i = end($a['sent_messages']) ? end($a['sent_messages'])['id'] : null;
            $j = end($b['sent_messages']) ? end($b['sent_messages'])['id'] : null;

            return  $j <=> $i; // desc
        });
        $tempContacts = array_map(function ($contact) use ($user) {
            // Filter messages to only include those sent to current user
            $contact['sent_messages'] = array_filter($contact['sent_messages'], function ($message) use ($contact, $user) {
                return $message['receiver_id'] === $user->id
                    && $message['sender_id'] === $contact['id'];
            });

            // Reset array keys if needed
            $contact['sent_messages'] = array_values($contact['sent_messages']);

            return $contact;
        }, $contacts);



        if ($receiver) {
            if ($receiver?->role !== 'admin' && !$user->is_admin) {
                abort(403);
            }
            if ($receiver->id === $user->id) {
                return back();
            }
            $messages = Message::with(['sender'])
                ->where(function ($q) use ($user, $receiver) {
                    $q->where('sender_id', $user->id)
                        ->where('receiver_id', $receiver->id);
                })
                ->orWhere(function ($q) use ($user, $receiver) {
                    $q->where('sender_id', $receiver->id)
                        ->where('receiver_id', $user->id);
                });

            Message::where('receiver_id', $user->id)
                ->where('sender_id', $receiver->id)
                ->where('seen', false)
                ->update(['seen' => true]);

            if ($request->boolean('all')) {
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
            'contacts' => $tempContacts,
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
