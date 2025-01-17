<?php

namespace App\Providers;

use App\helpers\ResponseHelper;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        RateLimiter::for('global', function () {
            return Limit::perMinute(60)->response(function () {
                return ResponseHelper::error('Too many requests. Please try again later.', 429);
            });
        });

        RateLimiter::for('auth', function () {
            return Limit::perMinute(10)->response(function () {
                return ResponseHelper::error('Too many login or registration attempts. Try again later.', 429);
            });
        });
    }
}
