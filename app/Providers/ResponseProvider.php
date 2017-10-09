<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Response\Transform\Api;

class ResponseProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Api::class, function ($app) {
            return new Api();
        });
    }
}
