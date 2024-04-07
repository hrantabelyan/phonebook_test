<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('api', function (Request $request) {
            if (auth()->check()) {
                return [
                    Limit::perSecond(10)->by(auth()->id()),
                    Limit::perMinute(200)->by(auth()->id()),
                ];
            }
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
