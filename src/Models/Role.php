<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class Role extends Model
{
    public $timestamps = false;

    protected $casts = [
        'acls'     => 'array',
        'raw_acls' => 'array',
        'features' => 'array'
    ];

    protected $columns = [
        'id',
        'org_id',
        'features',
        'name',
        'acls',
        'raw_acls'
    ];

    public function org()
    {
        return $this->belongsTo(Org::class);
    }

    public function strategies()
    {
        return $this->belongsToMany(Strategy::class, 'role_strategy')->withPivot('sequence')->oldest('sequence');
    }
}
