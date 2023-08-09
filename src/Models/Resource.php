<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class Resource extends Model
{
    public $timestamps = false;

    protected $columns = [
        'id',
        'name',
        'identifier',
        'model',
        'config'
    ];

    public function stragetgies()
    {
        return $this->morphedByMany(Strategy::class, 'resourceable');
    }
}
