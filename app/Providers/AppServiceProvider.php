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
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Register view composers
        \Illuminate\Support\Facades\View::composer(
            'layouts.app',
            \App\Http\View\Composers\NavigationComposer::class
        );
        \Illuminate\Support\Facades\View::composer(
            'layouts.dashboard',
            \App\Http\View\Composers\NavigationComposer::class
        );

        // Define dashboard access gate
        \Illuminate\Support\Facades\Gate::define(
            'access-dashboard',
            fn(\App\Models\User $user) => $user->role === 'admin'
        );

        // Handle Railway Ephemeral Storage (Step 6)
        if ($this->app->environment('production')) {
            $storagePath = storage_path('app/public');
            if (!is_dir($storagePath) || !is_writable($storagePath)) {
                \Illuminate\Support\Facades\Log::critical(
                    'Storage path not writable: ' . $storagePath .
                    ' — uploaded files will be lost on redeploy.'
                );
            }
        }
    }
}
