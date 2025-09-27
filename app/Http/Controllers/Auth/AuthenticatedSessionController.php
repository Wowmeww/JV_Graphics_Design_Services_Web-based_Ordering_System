<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }


    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (!$request->user()->is_admin) {
                return redirect()->intended(route('shop.index'));
            }
            return redirect()->intended(route('dashboard'));
        }

        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'No account found with this email address.',
        ])->onlyInput('email');
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


    // EMAIL VERIFICATION FUNCTIONS
    public function verificationNotice(Request $request)
    {
        return $request->user()
            ->hasVerifiedEmail() ?
            redirect()->intended(route('dashboard', absolute: false)) :
            Inertia::render('auth/VerifyEmail', ['message' => session('message')]);
    }

    public function verificationVerify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->intended('/dashboard')->with('status', [
            'type' => 'success',
            'message' => 'Email successfully verified.',
        ]);
    }

    public function verificationSend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false))->with('status', [
                'type' => 'info',
                'message' => 'The email ' . $request->user()->email . ' already verified.',
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', [
            'type' => 'info',
            'message' => 'Verification link sent to ' . $request->user()->email,
        ]);
    }
}
