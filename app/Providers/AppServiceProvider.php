<?php

namespace App\Providers;

use App\Contracts\ProductServiceContract;
use App\Services\FakeStoreApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductServiceContract::class,
            FakeStoreApiService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
