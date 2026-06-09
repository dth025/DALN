<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

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
        // Lazy-load relationships only when needed
        Model::preventLazyLoading(! $this->app->isProduction());

        // Use connection pooling for database
        if ($this->app->isProduction()) {
            Model::preventSilentlyDiscardingAttributes();
        }
    }
}
