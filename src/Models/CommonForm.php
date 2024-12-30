<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class CommonForm extends Model
{
    protected $casts = [];

    protected $columns = [
        'id',
        'name',
        'identifier',
        'dsl',
        'created_at',
        'updated_at',
    ];

    protected $filters = [
        'name',
        'identifier'
    ];

    protected $index_rules = [
        'name'       => 'string',
        'identifier' => 'string'
    ];

    public function filterName($builder, $value, $field)
    {
        return $builder->where($field, 'like', '%' . $value . '%');
    }

    public function filterIdentifier($builder, $value, $field)
    {
        return $builder->where($field, 'like', '%' . $value . '%');
    }
}
