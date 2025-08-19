<?php

namespace App\Providers;

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
        // Register custom Microsoft OAuth provider
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend('microsoft', function ($app) use ($socialite) {
            $config = $app['config']['services.microsoft'];
            return $socialite->buildProvider(
                \App\Providers\MicrosoftSocialiteProvider::class,
                $config
            );
        });
    }
}
