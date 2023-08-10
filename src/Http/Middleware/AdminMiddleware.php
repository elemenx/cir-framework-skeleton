<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $guard = config('cir_framework_skeleton.guard.auth');
        $user = Auth::guard($guard)->user();

        if (empty($user) || !$user->isAdmin()) {
            return response()->json([
                'code'    => 40305,
                'message' => trans('cir_framework_skeleton::errors.40305')
            ], 403);
        }

        return $next($request);
    }
}
