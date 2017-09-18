<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 17.09.17.
 * Time: 13:43
 */

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Meal;
use App\Repository\RepositoryInterface;
use App\Repository\Repositories\MealRepository;
use App\Repository\Repositories\MealRepositoryInterface;


class RepositoryProvider extends ServiceProvider
{
    public function boot() {}

    public function register()
    {
        $this->app->bind(MealRepositoryInterface::class, MealRepository::class);

        $this->app->singleton(MealRepository::class, function ($app) {
            return new MealRepository(new Meal());
        });
    }
}