<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Helper\Meal\QueryBuilder;

class FilterProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(QueryBuilder::class, function ($app) {
            return new QueryBuilder();
        });
    }
}
