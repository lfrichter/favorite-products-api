<?php

namespace App\Providers;

use App\Contracts\ProductServiceContract;
use App\Services\FakeStoreApiService;
use App\Services\FavoriteProductService;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductServiceContract::class, function ($app) {
            return new FakeStoreApiService(
                $app->make(HttpClient::class),
                config('services.fakestore.base_url')
            );
        });

        $this->app->singleton(FavoriteProductService::class, function ($app) {
            return new FavoriteProductService(
                $app->make(ProductServiceContract::class)
            );
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
