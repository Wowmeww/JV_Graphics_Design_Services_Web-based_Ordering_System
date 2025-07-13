<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Policies\ShopPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        // Model::preventLazyLoading();
        Model::automaticallyEagerLoadRelationships();

        Gate::policy(Product::class, ProductPolicy::class);

        Gate::define('shop', [ShopPolicy::class, 'shop']);

        Inertia::share([
            'urlPrevious' => url()->previous()
        ]);
    }
}
