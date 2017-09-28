<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Repositories\Query\QueryBuilder;


class QueryProvider extends ServiceProvider
{
    public function register()
    {

        $this->app->singleton(QueryBuilder::class,  function($app) {
            return new QueryBuilder();
        });
    }
}