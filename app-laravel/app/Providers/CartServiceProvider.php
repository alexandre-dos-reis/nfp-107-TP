<?php

namespace App\Providers;

use App\Service\Cart\CartService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Service\Cart\CartService', function ($app) {
            return new CartService($app->make(Request::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
