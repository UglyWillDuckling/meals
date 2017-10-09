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
use App\Repository\Repositories\MealRepository;
use App\Repository\Repositories\MealRepositoryInterface;
use App\Repository\Repositories\Query\QueryBuilder;


class RepositoryProvider extends ServiceProvider
{
    public function boot() {}

    public function register()
    {
        $this->app->bind(MealRepositoryInterface::class, MealRepository::class);

        $this->app->singleton(MealRepository::class, function ($app) {
            return new MealRepository(new Meal(), new QueryBuilder());
        });
    }
}