<?php

namespace App\Http\Middleware;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request?->user() ?
                    $request->user()->load(['cart', 'wishlist', 'orders']) :
                    null,
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'shopAside' => [
                'isCartOpen' => session('isCartOpen', false),
                'isOpen' => session('shopAsideOpen', false),
            ],
            'status' => session('status'),
            'confirm' => [
                'show' => false,
                'message' => '',
                'confirmed' => false,
            ],
            'github' => env('GITHUB_REPO')
        ];
    }
}
