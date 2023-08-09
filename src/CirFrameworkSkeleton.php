<?php

namespace Elemenx\CirFrameworkSkeleton;

use Illuminate\Support\Arr;

class CirFrameworkSkeleton
{
    /**
     * Indicates if the implicit grant type is enabled.
     *
     * @var bool|null
     */
    public static $implicitGrantEnabled = false;

    /**
     * The default scope.
     *
     * @var string
     */
    public static $defaultScope;

    /**
     * All of the scopes defined for the application.
     *
     * @var array
     */
    public static $scopes = [
        //
    ];

    public static $adminMiddlewares = [
        //
    ];

    /**
     * Indicates if elemenx skeleton migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * Indicates if elemenx skeleton routes will be registered.
     *
     * @var bool
     */
    public static $registersRoutes = true;

    /**
     * Enable the implicit grant type.
     *
     * @return static
     */
    public static function enableImplicitGrant()
    {
        static::$implicitGrantEnabled = true;

        return new static;
    }

    /**
     * Set the default scope(s). Multiple scopes may be an array or specified delimited by spaces.
     *
     * @param  array|string  $scope
     * @return void
     */
    public static function setDefaultScope($scope)
    {
        static::$defaultScope = is_array($scope) ? implode(' ', $scope) : $scope;
    }

    /**
     * Get all of the defined scope IDs.
     *
     * @return array
     */
    public static function scopeIds()
    {
        return static::scopes()->pluck('id')->values()->all();
    }

    /**
     * Determine if the given scope has been defined.
     *
     * @param  string  $id
     * @return bool
     */
    public static function hasScope($id)
    {
        return $id === '*' || array_key_exists($id, static::$scopes);
    }

    /**
     * Get all of the scopes defined for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function scopes()
    {
        return collect(static::$scopes)->map(function ($description, $id) {
            return new Scope($id, $description);
        })->values();
    }

    /**
     * Get all of the scopes matching the given IDs.
     *
     * @param  array  $ids
     * @return array
     */
    public static function scopesFor(array $ids)
    {
        return collect($ids)->map(function ($id) {
            if (isset(static::$scopes[$id])) {
                return new Scope($id, static::$scopes[$id]);
            }
        })->filter()->values()->all();
    }

    /**
     * Configure elemenx skeleton to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes()
    {
        static::$registersRoutes = false;

        return new static;
    }

    /**
     * Configure elemenx skeleton to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }

    public static function getAdminMiddlewares()
    {
        self::$adminMiddlewares = (array)config('cir_framework_skeleton.route.admin.middlewares');
        if (config('cir_framework_skeleton.route.admin.type') == 'merge') {
            self::$adminMiddlewares = array_merge(self::$adminMiddlewares, Arr::wrap(config('cir_framework_skeleton.route.admin.default_middleware')));
        }

        return self::$adminMiddlewares;
    }
}
