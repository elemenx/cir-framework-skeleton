<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class Resourceable extends Model
{
    public $timestamps = false;

    protected $casts = [
        'keys' => 'array'
    ];

    protected $columns = [
        'resource_id',
        'resourceable_id',
        'resourceable_type',
        'keys',
        'identifier_alias',
        'expression'
    ];
}
