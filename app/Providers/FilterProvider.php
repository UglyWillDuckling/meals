<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Helper\Meal\Filter;

class FilterProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Filter::class, function ($app) {
            return new Filter();
        });
    }
}
