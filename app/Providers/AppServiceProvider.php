<?php

namespace App\Providers;

use App\Http\Middleware\JwtWebMiddleware;
use App\Http\Middleware\TokenToSession;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TokenToSession::class, function ($app) {
            return new TokenToSession();
        });
        $this->app->bind(JwtWebMiddleware::class, function ($app) {
            return new JwtWebMiddleware();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
