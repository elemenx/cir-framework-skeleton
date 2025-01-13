<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class Workflow extends Model
{
    protected $casts = [];

    protected $columns = [
        'id',
        'name',
        'identifier',
        'common_form_identifier',
        'form_dsl',
        'workflow_dsl',
        'created_at',
        'updated_at',
    ];

    protected $filters = [
        'name',
        'identifier',
        'common_form_identifier'
    ];

    protected $index_rules = [
        'name'                   => 'string',
        'identifier'             => 'string',
        'common_form_identifier' => 'string'
    ];

    public function filterName($builder, $value, $field)
    {
        return $builder->where($field, 'like', '%' . $value . '%');
    }

    public function filterIdentifier($builder, $value, $field)
    {
        return $builder->where($field, 'like', '%' . $value . '%');
    }

    public function filterCommonFormIdentifier($builder, $value, $field)
    {
        return $builder->where($field, 'like', '%' . $value . '%');
    }

    public function commonForm()
    {
        return $this->hasOne(CommonForm::class, 'identifier', 'common_form_identifier');
    }
}
