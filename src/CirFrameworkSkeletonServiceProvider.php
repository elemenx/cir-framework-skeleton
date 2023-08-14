<?php

namespace Elemenx\CirFrameworkSkeleton;

use Elemenx\CirFrameworkSkeleton\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $this->registerValidatorRules();
        $this->registerMiddlewares();
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

    /**
     * Register the elemenx cir framework skeleton validator rules.
     *
     * @return void
     */
    protected function registerValidatorRules()
    {
        Validator::extend('date_range', function ($attribute, $value) {
            $value = explode(',', $value);

            return count($value) == 2;
        });

        Validator::extend('exists_except_zero', function ($attribute, $value, $parameters) {
            if (!isset($parameters[0])) {
                return false;
            }

            $explode_first_parameter = explode('.', $parameters[0]);
            if (count($explode_first_parameter) > 1) {
                $builder = DB::connection($explode_first_parameter[0])->table($explode_first_parameter[1]);
            } else {
                $builder = DB::table($parameters[0]);
            }
            $builder = $builder->where($parameters[1] ?? 'id', $value);
            array_map(function ($item) use (&$builder) {
                if (Arr::last($item) == 'NULL') {
                    $builder->whereNull(Arr::first($item));
                } else {
                    $builder->where(Arr::first($item), Arr::last($item));
                }
            }, array_chunk(array_slice($parameters, 2), 2));

            if ($value != 0 && !$builder->exists()) {
                return false;
            }

            return true;
        });
    }

    /**
     * Register the elemenx cir framework skeleton validator rules.
     *
     * @return void
     */
    protected function registerMiddlewares()
    {
        app('router')->aliasMiddleware('admin', AdminMiddleware::class);
    }
}
