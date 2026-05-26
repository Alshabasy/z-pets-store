<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-dashboard', function (User $user) {
            return $user->role === 'admin';
        });

        \Illuminate\Support\Facades\View::composer('layouts.app', \App\Http\View\Composers\NavigationComposer::class);
        
        \Illuminate\Support\Facades\View::composer('layouts.dashboard', function ($view) {
            $view->with('newOrdersCount', \App\Models\Order::where('status', 'new')->count());
        });
    }
}
