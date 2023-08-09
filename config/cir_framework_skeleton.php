<?php

use Elemenx\CirFrameworkSkeleton\Http\Middleware\AdminMiddleware;
use Elemenx\CirFrameworkSkeleton\Http\Resources\Auth\UserResource;

return [
    'guard' => [
        'auth' => env('CIR_FRAMEWORK_SKELETON_GUARD_AUTH', 'api'),
    ],
    'model' => [
        'namespace' => env('CIR_FRAMEWORK_SKELETON_MODEL_NAMESPACE', 'App\\Model\\'),
        'admin'     => [
            // admin field
            'field' => env('CIR_FRAMEWORK_SKELETON_MODEL_ADMIN_FIELD', 'is_admin'),
        ]
    ],
    'route' => [
        'prefix' => env('CIR_FRAMEWORK_SKELETON_ROUTE_PREFIX', ''),
        'admin'  => [
            'type'               => env('CIR_FRAMEWORK_SKELETON_ROUTE_ADMIN_TYPE', 'merge'),
            'default_middleware' => AdminMiddleware::class,
            'middlewares'        => []
        ]
    ],
    'resource' => [
        'user' => UserResource::class
    ]
];
