<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Meal;
use App\Repository\RepositoryInterface;
use App\Repository\Repositories\MealRepository;

class HappyMeal extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Meal::class, function ($app) {
            return new Meal();
        });
    }
}
