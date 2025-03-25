<?php

namespace VirtualQueue\TokenVerifier\Laravel;

use Illuminate\Support\ServiceProvider;
use VirtualQueue\TokenVerifier\TokenVerifier;

class TokenVerifierServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/token-verifier.php', 'token-verifier'
        );

        $this->app->singleton(TokenVerifier::class, function ($app) {
            return new TokenVerifier(
                config('token-verifier.base_url'),
                config('token-verifier.options', [])
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/token-verifier.php' => config_path('token-verifier.php'),
            ], 'config');
        }
    }
} 