<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Registry\Registry;

class RegistryProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Registry::class, function($app) {
            return new Registry();
        });
    }
}