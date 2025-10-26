<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable:string',
            'role' => 'nullable:string',
            'sort' => 'nullable:string',
        ]);

        $request['sort'] =  $request['sort'] ?? 'Sort by date (new to old)';

        $users = User::with(['orders'])->filter(request(['search', 'role', 'sort']))->paginate(8)->withQueryString();
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
            'role' => ['required', 'string', Rule::in(['customer', 'admin', 'suspended'])],
            'verified_at' => ['nullable']
        ]);

        // Option 2: Explicit check
        $validated['verified_at'] = isset($validated['verified_at']) && $validated['verified_at']
            ? Carbon::now()
            : null;

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
