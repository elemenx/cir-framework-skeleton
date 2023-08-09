<?php

namespace Elemenx\CirFrameworkSkeleton\Traits\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait Filterable
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request|null $input
     */
    public function scopeFilterField(Builder $builder, $input = null, $rule_name = '')
    {
        $filters = $this->getFilterData($input, $rule_name);

        foreach ($filters as $field => $value) {
            if (is_null($value)) {
                continue;
            }

            $method = 'filter' . Str::studly($field);
            if (method_exists($this, $method)) {
                call_user_func([$this, $method], $builder, $value, $field);
            } elseif ($this->isFilterable($field)) {
                if (Str::endsWith($field, ['_at', '_date'])) {
                    call_user_func([$this, 'filterDateRange'], $builder, $value, $field);
                } elseif ($this->isFilterableWhereIn($field, $value)) {
                    call_user_func([$this, 'filterWhereIn'], $builder, $value, $field);
                } elseif (is_array($value)) {
                    $builder->whereIn($field, $value);
                } else {
                    $builder->where($field, $value);
                }
            }
        }

        return $builder;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isFilterable(string $key)
    {
        return property_exists($this, 'filters') && in_array($key, array_diff($this->filters, $this->ignore_filters));
    }

    /**
     * @param string $key
     * @param mixin $value
     * @return bool
     */
    public function isFilterableWhereIn(string $key, $value)
    {
        return Str::endsWith($key, 'ids') && is_array($value);
    }

    public function getFilterData($input, $filter_rule_name = '')
    {
        $rule_name = 'getIndexRules';
        $as = app('request')->route()->getName();
        $explode_as = explode('.', $as);
        unset($explode_as[0]);

        if (count($explode_as) == 1) {
            $rule_name = 'get' . Str::studly(implode('_', $explode_as)) . 'Rules';
        }

        if (!empty($filter_rule_name)) {
            $rule_name = 'get' . Str::studly($filter_rule_name) . 'Rules';
        }

        return $input
            ? (is_array($input) ? $input : [$input])
            : Validator::make(request()->all(), $this->{$rule_name}() ?? [])->validate();
    }
}
