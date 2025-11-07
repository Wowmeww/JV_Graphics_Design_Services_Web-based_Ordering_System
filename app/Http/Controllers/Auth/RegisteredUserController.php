<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'role' => 'customer',
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', 'min:8', Password::min(8)->letters()->numbers()],
        ]);

        $user = User::create($credentials);

        Cart::create(['user_id' => $user->id]);
        Wishlist::create(['user_id' => $user->id]);

        event(new Registered($user));
        Auth::login($user);

        if ($request->user()->is_admin) {
            return redirect()->intended('dashboard'); // or intended page
        }
        return redirect()->intended('shop'); // or intended page
    }
}
