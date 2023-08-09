<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Elemenx\CirFrameworkSkeleton\Traits\ProvidesConvenienceMethods;

/**
 * @OA\Info(title="mashangezhi-be-admin", version="0.1")
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ProvidesConvenienceMethods;
}
