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

    protected $filters = [
        'name',
        'identifier',
        'model'
    ];

    protected $index_rules = [
        'name'       => 'string',
        'identifier' => 'string',
        'model'      => 'string',
    ];

    public function filterName($builder, $value, $field)
    {
        return $builder->where($field, 'like', '%' . $value . '%');
    }

    public function filterIdentifier($builder, $value, $field)
    {
        return $builder->where($field, 'like', '%' . $value . '%');
    }

    public function filterModel($builder, $value, $field)
    {
        return $builder->where($field, 'like', '%' . $value . '%');
    }

    public function stragetgies()
    {
        return $this->morphedByMany(Strategy::class, 'resourceable');
    }
}
