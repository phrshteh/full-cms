<?php

namespace Phrshte\FullCms;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CMSBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole())
        {
            $this->registerPublishing();
        }

        $this->registerResources();
    }

    public function register()
    {
        
    }

    public function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->registerRoutes();
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/cms.php' => config_path('cms.php')
        ], 'cms-config');
    }

    protected function registerRoutes()
    {
//        Route::group([] , function (){
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
//        });
    }
}