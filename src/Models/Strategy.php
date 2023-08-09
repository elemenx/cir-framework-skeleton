<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class Strategy extends Model
{
    public $timestamps = false;

    protected $casts = [
        'acls'     => 'array',
        'raw_acls' => 'array',
        'features' => 'array',
    ];

    protected $columns = [
        'id',
        'name',
        'features',
        'acls',
        'raw_acls',
        'rule_config',
    ];

    public function resources()
    {
        return $this->morphToMany(Resource::class, 'resourceable')->withPivot(['keys', 'expression']);
    }
}
