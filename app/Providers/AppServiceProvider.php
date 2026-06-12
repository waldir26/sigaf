<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

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
        // 1. Forzar HTTPS en producción para Railway
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // 2. Tu protección contra fuerza bruta (Muda aquí)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
