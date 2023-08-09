<?php

namespace Elemenx\CirFrameworkSkeleton\Traits\Middleware;

use Elemenx\CirFrameworkSkeleton\Models\Resource;
use Elemenx\CirFrameworkSkeleton\WhereQueryExpressionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait ResourceHandle
{
    private function getResourceData($strategies)
    {
        $acl_data = [];
        $resources = Resource::selectRaw('`resources`.*, `resourceables`.`resourceable_id` as `pivot_resourceable_id`, `resourceables`.`resource_id` as `pivot_resource_id`, `resourceables`.`resourceable_type` as `pivot_resourceable_type`, `resourceables`.`expression` as `pivot_expression`')
            ->join('resourceables', function ($join) use ($strategies) {
                $join->on('resources.id', '=', 'resourceables.resource_id')
                    ->whereIn('resourceables.resourceable_id', $strategies->pluck('id'))
                    ->where('resourceables.resourceable_type', 'strategy');
            })
            ->get();

        $kv_strategies = $strategies->keyBy('id');

        foreach ($resources->groupBy('pivot_resourceable_id') as $resourceable_id => $group_resources) {
            foreach ($group_resources as $resource) {
                $strategy = $kv_strategies[$resource->pivot_resourceable_id];

                if (empty($strategy->acls)) {
                    continue;
                }

                foreach ($strategy->acls as $acl) {
                    $acl_key = Str::endsWith($acl, '.*') ? substr($acl, 0, -2) : $acl;
                    $acl_data[$acl_key] = [
                        'acl'        => $acl_key,
                        'raw_acl'    => $acl,
                        'model'      => $resource->model,
                        'expression' => $resource->pivot_expression,
                    ];
                }
            }
        }

        return $acl_data;
    }

    private function addResourceScope($acl_item_data)
    {
        $class = config('cir_framework_skeleton.model_namespace') . Str::studly($acl_item_data['model']);

        if (!class_exists($class)) {
            return false;
        }

        $class::addGlobalScope($acl_item_data['acl'], function (Builder $builder) use ($acl_item_data) {
            $builder->whereRaw((new WhereQueryExpressionService($acl_item_data['expression']))->parse());
        });
    }

    private function conditionAcls($acl, $route, $acl_data, $matchedAcl)
    {
        if (Str::endsWith($acl, '.*')) {
            $acl_temp = substr($acl, 0, -2);

            if ($acl_temp == $route || Str::startsWith($route, $acl_temp . '.')) {
                $matchedAcl = true;

                if (isset($acl_data[$acl_temp])) {
                    $this->addResourceScope($acl_data[$acl_temp]);
                }
            }
        }

        if ($acl == $route) {
            $matchedAcl = true;

            if (isset($acl_data[$acl])) {
                $this->addResourceScope($acl_data[$acl]);
            }
        }

        return $matchedAcl;
    }
}
