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
        $user = $request->user();
        return Inertia::render('profile/UserSettings', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'user' => $user->load(['idCard'])
        ]);
    }

    /**
     * Update the user's profile information.
     */


    public function updateProfile(Request $request)
    {
        $user = $request->user();
        // dd($request->all());

        $idCardRequired = count(array_filter($request->idCard, fn($prop) => $prop));
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'sex'        => ['required', 'string'],
            'email'      => ['required', 'email', 'lowercase', 'max:200', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'      => ['required', 'string', 'regex:/^(\+?63|0)?9\d{9}$/'],
            'birth_date' => ['nullable', 'string'],
            'address'    => ['nullable', 'string', 'max:255'],
            'avatar'     => ['nullable', 'image', 'max:1024'],
            'idCard'     => ['nullable', 'array'],
            'idCard.type' => $idCardRequired ? 'required' : 'nullable|string',
            'idCard.selfie' => $idCardRequired ? 'required' : 'nullable|image|max:1024',
            'idCard.front' => $idCardRequired ? 'required' : 'nullable|image|max:1024',
            'idCard.back' => $idCardRequired ? 'required' : 'nullable|image|max:1024',
        ]);


        $updateData = [
            'name'       => $validated['name'],
            'sex'        => $validated['sex'],
            'birth_date' => $validated['birth_date'] ?? null,
            'address'    => $validated['address'] ?? null,
            'phone'    => $validated['phone'] ?? null,
        ];

        // Check if email was changed
        if ($validated['email'] !== $user->email) {
            $updateData['email'] = $validated['email'];
            $updateData['email_verified_at'] = null;
        }

        if ($idCardRequired) {
            $idCard = [];

            if ($user->idCard) {
                Storage::disk('public')->delete($user->idCard->selfie);
                Storage::disk('public')->delete($user->idCard->front);
                Storage::disk('public')->delete($user->idCard->back);
                $user->idCard()->delete();
            }
            foreach ($request->idCard as $key => $file) {
                if (is_file($file)) {
                    $idCard[$key] = Storage::disk('public')->put('/users/id', $file);
                } else {
                    $idCard[$key] = $file;
                }
            }

            $user->idCard()->create($idCard);
        }

        $user->update($updateData);

        // Handle avatar actions
        $avatarAction = $request->input('avatarAction');

        if ($avatarAction === 'delete' && $user->avatar_url) {
            Storage::disk('public')->delete($user->avatar_url);
            $user->update(['avatar_url' => null]);
        }

        if ($avatarAction === 'upload' && $request->hasFile('avatar')) {
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
