<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class Org extends Model
{
    protected static $logOnlyDirty = true;

    protected $casts = [
        'user_id' => 'integer',
    ];

    protected $hidden = [
        'pivot', 'collaborator'
    ];

    protected $columns = [
        'id',
        'user_id',
        'name',
        'created_at'
    ];

    protected $filters = [
        'name',
    ];

    protected $index_rules = [
        'name' => 'string',
    ];

    public function filterName($builder, $value, $field)
    {
        return $builder->where($field, 'like', '%' . $value . '%');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'org_user')->using(OrgUser::class)->as('collaborator')->withPivot(['role_id']);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
