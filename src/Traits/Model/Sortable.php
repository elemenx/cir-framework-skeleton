<?php

namespace Elemenx\CirFrameworkSkeleton\Traits\Model;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request|null $input
     */
    public function scopeSortField(Builder $builder, $input = null, $default_sort = null)
    {
        $input = $input ? $input : request()->get('sort', '');
        $table_name = $this->getTable();

        if (is_string($input) && !empty($input)) {
            $input = explode(',', $input);

            foreach ($input as $sort) {
                $order = substr($sort, 0, 1) == '-' ? 'DESC' : 'ASC';
                $orderBy = $order == 'DESC' ? substr($sort, 1) : $sort;
                $method = 'sort' . Str::studly($orderBy);

                if (method_exists($this, $method)) {
                    call_user_func([$this, $method], $builder, $order, $orderBy);
                } elseif ($this->isSortable($orderBy)) {
                    $builder->orderBy($table_name . '.' . $orderBy, $order);
                }
            }
        } else {
            $default_sort_func = 'Scope' . Str::studly($default_sort);
            if (!empty($default_sort) && method_exists($this, $default_sort_func)) {
                $builder->{Str::studly($default_sort)}();
            } else {
                $builder->orderBy($table_name . '.' . $this->getDefaultSortField(), $this->getDirection());
            }
        }

        return $builder;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isSortable(string $key)
    {
        return property_exists($this, 'sorts') && in_array($key, $this->sorts);
    }
}
