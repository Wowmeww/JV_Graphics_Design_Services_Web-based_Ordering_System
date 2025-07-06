<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('profile/UserSettings', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'user' => $request->user()
        ]);
    }

    /**
     * Update the user's profile information.
     */

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Validate fields
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'sex'        => ['required', 'string'],
            'email' => ['required', 'email', 'lowercase', 'max:200', Rule::unique('users', 'email')->ignore($user->id)],
            'birth_date' => ['nullable', 'string'],
            'address'    => ['nullable', 'string', 'max:255'],
            'avatar'     => ['nullable', 'image', 'max:1024'], // 1MB max
        ]);

        // Update user fields
        $user->update([
            'name'       => $validated['name'],
            'sex'        => $validated['sex'],
            'email'      => $validated['email'],
            'birth_date' => $validated['birth_date'] ?? null,
            'address'    => $validated['address'] ?? null,
        ]);

        // Handle avatar actions
        $avatarAction = $request->input('avatarAction');

        if ($avatarAction === 'delete' && $user->avatar_url) {
            Storage::disk('public')->delete($user->avatar_url);
            $user->update(['avatar_url' => null]);
        }

        if ($avatarAction === 'upload' && $request->hasFile('avatar')) {
            // Delete previous avatar if exists
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
            }

            $path = $request->file('avatar')->store('images/avatars', 'public');
            $user->update(['avatar_url' => $path]);
        }

        return redirect()->back()->with('status', [
            'type' => 'success',
            'message' => 'Profile updated',
        ]);
    }

    public function editPassword(Request $request)
    {
        return Inertia::render('profile/Password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', [
            'type' => 'success',
            'message' => 'Password updated successfully.',
        ]);
    }

    public function editAppearance(Request $request)
    {
        return Inertia::render('profile/Appearance');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
