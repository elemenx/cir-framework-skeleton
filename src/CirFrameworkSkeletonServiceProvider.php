<?php

namespace Elemenx\CirFrameworkSkeleton;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CirFrameworkSkeletonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerPublishing();
        $this->registerResources();
    }

    /**
     * Register the elemenx cir framework skeleton routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if (CirFrameworkSkeleton::$registersRoutes) {
            Route::group([
                'as'        => 'cir_framework_skeleton.',
                'prefix'    => config('cir_framework_skeleton.route.prefix'),
                'namespace' => 'Elemenx\CirFrameworkSkeleton\Http\Controllers',
            ], function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            });
        }
    }

    /**
     * Register the elemenx cir framework skeleton migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole() && CirFrameworkSkeleton::$runsMigrations) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'cir-framework-skeletont-migrations');

            $this->publishes([
                __DIR__ . '/../config/cir_framework_skeleton.php' => config_path('cir_framework_skeleton.php'),
            ], 'cir-framework-skeletont-config');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerResources()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'cir_framework_skeleton');
    }

    /**
     * Register the elemenx cir framework skeleton resource lang files.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cir_framework_skeleton.php', 'cir_framework_skeleton');
    }
}
