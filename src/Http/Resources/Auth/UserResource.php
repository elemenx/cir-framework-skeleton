<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Auth;

use App\Models\Org;
use Elemenx\CirFrameworkSkeleton\Http\Resources\Module\ModuleResource;
use Elemenx\CirFrameworkSkeleton\Http\Resources\Strategy\StrategyResource;
use Elemenx\CirFrameworkSkeleton\Models\Module;
use Elemenx\CirFrameworkSkeleton\Traits\Resource\RouteName;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use RouteName;

    public static $names = [
        'login',
        'session',
        'session.info',
        'login_mobile',
        'register',
        'reset',
        'oauth.login'
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
            'id'          => $this->id,
            'mobile'      => $this->mobile,
            'name'        => $this->name,
            'locked'      => $this->locked,
            'enabled'     => $this->enabled,
            'is_admin'    => $this->is_admin,
            'current_org' => !empty($this->currentOrg) ? $this->currentOrg->only('id', 'name') : $this->currentOrg,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'issued_at'   => $this->issued_at,
            'reset_at'    => $this->reset_at
        ];

        if ($this->isShowNames(app('request')->route()->getName())) {
            $modules = Module::sortField()->with('dataResource', 'resources', 'settingCategories', 'settingItems')->get();

            $data = array_merge($data, [
                'acl'        => $this->acl,
                'raw_acls'   => $this->raw_acls,
                'orgs'       => $this->getOrgs(),
                'strategies' => $this->strategies ? StrategyResource::collection($this->strategies) : [],
                'modules'    => ModuleResource::collection($modules)
            ]);
        }

        return $data;
    }

    protected function getOrgs()
    {
        if ($this->resource->isAdmin()) {
            return Org::select('id', 'name')->get();
        }

        return $this->orgs;
    }
}
