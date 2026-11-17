<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Resources\Auth;

use Elemenx\CirFrameworkSkeleton\Http\Resources\Module\ModuleResource;
use Elemenx\CirFrameworkSkeleton\Http\Resources\Strategy\StrategyResource;
use Elemenx\CirFrameworkSkeleton\Models\Module;
use Elemenx\CirFrameworkSkeleton\Models\Org;
use Elemenx\CirFrameworkSkeleton\Traits\Resource\RouteName;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

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
            $data = array_merge($data, [
                'acl'        => $this->acl,
                'raw_acls'   => $this->raw_acls,
                'orgs'       => $this->getOrgs(),
                'strategies' => $this->strategies ? StrategyResource::collection($this->strategies) : [],
                'modules'    => $this->modules()
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

    /**
     * Get the cached modules payload.
     *
     * The cache must only contain plain arrays: with cache.serializable_classes
     * disabled (the default in Laravel 13), any cached object is read back as
     * __PHP_Incomplete_Class.
     *
     * @return array
     */
    protected function modules(): array
    {
        $modules = Cache::tags('settings')->get('settings:module');

        // Entries written by older versions contain resource objects and can no
        // longer be hydrated, so rebuild them instead of serving broken data.
        if (is_array($modules)) {
            return $modules;
        }

        $modules = $this->buildModules();

        Cache::tags('settings')->forever('settings:module', $modules);

        return $modules;
    }

    /**
     * Build the modules payload as a plain array.
     *
     * @return array
     */
    protected function buildModules(): array
    {
        $collection = ModuleResource::collection(
            Module::sortField()
                ->with('dataResource', 'resources', 'settingCategories', 'settingItems')
                ->get()
        );

        // Round-trip through JSON so nested resources are flattened as well.
        $modules = json_decode($collection->toJson(), true);

        return is_array($modules) ? $modules : [];
    }
}
