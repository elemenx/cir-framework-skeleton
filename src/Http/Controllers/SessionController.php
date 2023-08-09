<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Http\Resources\Auth\UserResource;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function session()
    {
        $user = Auth::guard(config('cir_framework_skeleton.guard.auth'))->user();

        return $this->success(new UserResource($user));
    }
}
