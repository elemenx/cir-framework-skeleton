<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use Elemenx\CirFrameworkSkeleton\Exceptions\MissingAdminFieldException;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $guarded = [];

    protected $appends = [
        'acl'
    ];

    protected $with = ['orgs:orgs.id,orgs.name,orgs.user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'reset_at'  => 'datetime',
        'enabled'   => 'boolean',
        'locked'    => 'boolean',
        'is_admin'  => 'boolean',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = app('hash')->make($password);
    }

    public function orgs()
    {
        return $this->belongsToMany(Org::class, 'org_user')->using(OrgUser::class)->as('collaborator')->withPivot('role_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'org_user', 'user_id', 'role_id')->withPivot('org_id')->orderBy('id', 'DESC');
    }

    public function getCurrentOrgAttribute()
    {
        $org_id = app('request')->header('org-id');
        $cache_last_passport_key = 'user:' . $this->id . ':last_passport_login';
        if (Cache::has($cache_last_passport_key)) {
            $org_id = Cache::get($cache_last_passport_key);
            Cache::forget($cache_last_passport_key);
        }

        if (empty($org_id) || $org_id == 'null') {
            $org = ($this->is_admin ? new Org : $this->orgs())->orderBy('id', 'ASC')->first();
        } else {
            $org = Org::find($org_id);
        }

        return $org;
    }

    public function getAclAttribute()
    {
        return $this->handleAcls();
    }

    public function getRawAclsAttribute()
    {
        return $this->handleAcls();
    }

    public function getStrategiesAttribute()
    {
        $strategies = [];
        $org = $this->currentOrg;

        if (!empty($org)) {
            if ($org->user_id == $this->id) {
                $strategies = [];
            } else {
                $org_user = OrgUser::where('user_id', $this->id)
                    ->where('org_id', $org->id)
                    ->with('role.strategies.resources')
                    ->first();
                $strategies = !empty($org_user->role) ? $org_user->role->strategies : [];
            }
        }

        return $strategies;
    }

    public function own(Model $model, $check_type = 'user_id')
    {
        if (Str::endsWith($check_type, '_id')) {
            if (isset($model[$check_type]) && $model[$check_type] == $this->id) {
                return true;
            }
        } else {
            if (isset($model[$check_type . '_type']) && $model[$check_type . '_type'] == 'user' && $model[$check_type . '_id'] == $this->id) {
                return true;
            }
        }

        return false;
    }

    protected function handleAcls()
    {
        $acl = [];
        if ($this->isAdmin()) {
            $acl[] = '*';
            return $acl;
        }

        $org = $this->currentOrg;
        if (!empty($org)) {
            $orgUser = OrgUser::where('user_id', $this->id)
                ->where('org_id', $org->id)
                ->with('role')
                ->first();

            if (!empty($orgUser) && !empty($orgUser->role)) {
                $acl = is_null($orgUser->role->raw_acls) ? [] : $orgUser->role->raw_acls;
            }
        }

        return $acl;
    }

    public function isAdmin()
    {
        $isAdmin = $this->{config('cir_framework_skeleton.model.admin.field')};
        if (is_null($isAdmin)) {
            throw new MissingAdminFieldException();
        }
        return $isAdmin;
    }
}
