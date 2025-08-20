<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->verified_at) {
           return redirect()->route('profile.edit')->with('status', [
            'type' => 'info',
            'message' => 'To place any order you need to be verified'
        ]);
        }

        return $next($request);
    }
}
