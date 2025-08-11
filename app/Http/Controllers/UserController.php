<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable:string',
            'role' => 'nullable:string',
        ]);

        $users = User::with(['orders'])->filter(request(['search', 'role']))->paginate(8)->withQueryString();
        return Inertia::render('manage/user/Users', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {

        return Inertia::render('manage/user/EditUser', [
            'user' => $user->load(['idCard'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'string'],
            'verified_at' => ['nullable', 'string']
        ]);

        $user->update($validated);

        return back()->with('status', [
            'type' => 'success',
            'message' => 'User account settings updated.',
        ]);
    }

    public function destroy(User $user)
    {
        //
    }
}
