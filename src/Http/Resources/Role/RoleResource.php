<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Role;

use Elemenx\CirFrameworkSkeleton\Http\Resources\Strategy\StrategyResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Elemenx\CirFrameworkSkeleton\Traits\Controller\Acl;
use Elemenx\CirFrameworkSkeleton\Traits\Resource\RouteName;

class RoleResource extends JsonResource
{
    use Acl, RouteName;

    public static $names = [
        'role.index',
        'role.show',
        'role.update',
        'role.store'
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'         => $this->id,
            'org_id'     => $this->org_id,
            'org'        => !empty($this->org) ? $this->org : null,
            'name'       => $this->name,
            'raw_acls'   => $this->raw_acls,
            'features'   => is_null($this->features) ? [] : $this->features,
            'is_default' => $this->is_default
            // 'config'   => $this->config,
            // 'modules'  => $this->modules,
        ];

        $acls = $this->acls;
        foreach ($this->strategies as $stragegy) {
            $acls = array_merge($acls, $stragegy->acls);
        }
        $data['acls'] = $this->handleAcl($acls);

        if ($this->isShowNames(app('request')->route()->getName())) {
            $data['strategies'] = StrategyResource::collection($this->strategies);
        }

        return $data;
    }
}
