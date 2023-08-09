<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use Elemenx\CirFrameworkSkeleton\Traits\Model\Exportable;
use Elemenx\CirFrameworkSkeleton\Traits\Model\Filterable;
use Elemenx\CirFrameworkSkeleton\Traits\Model\Sortable;
use Carbon\Carbon;
use DateTimeInterface;
use ElemenX\ApiPagination\Paginatable;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Model extends BaseModel
{
    use Sortable, Filterable, Exportable, Paginatable;

    protected $guarded = [];

    protected $columns = [];

    protected $default_sort_field = 'id';

    protected $direction = 'DESC';

    protected $export_title = [];

    protected $export_value = [];

    protected $ignore_filters = [
        'export',
        'sort'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeExclude($query, ...$value)
    {
        return $query->select(array_diff($this->columns, $value));
    }

    public function getDescriptionForEvent(string $event)
    {
        return '该对象被' . trans('event_types.' . $event);
    }

    public function hasRelation($key)
    {
        if ($this->relationLoaded($key)) {
            return true;
        }

        if (method_exists($this, $key)) {
            return is_a($this->$key(), "Illuminate\Database\Eloquent\Relations\Relation");
        }

        return false;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getSorts()
    {
        return $this->sorts;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getDefaultSortField()
    {
        return $this->default_sort_field;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function getIndexRules()
    {
        return $this->index_rules;
    }

    public function getWithRelations()
    {
        return $this->with_relations ?? [];
    }

    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'get') && Str::endsWith($method, 'Rules')) {
            $property_name = Str::snake(Str::replaceFirst('get', '', $method));

            if (property_exists($this, $property_name)) {
                return $this->{$property_name};
            }
        }

        return parent::__call($method, $parameters);
    }

    public function filterDateRange($builder, $value, $field)
    {
        list($start, $end) = explode(',', $value);

        return $builder->whereBetween($field, [$start, $end]);
    }

    public function filterWhereIn($builder, $value, $field)
    {
        if ($field == 'ids') {
            $field = 'id';
        }

        return $builder->whereIn($field, $value);
    }

    public function filterName($builder, $value, $field)
    {
        return $builder->where($field, 'like', $value . '%');
    }

    public function filterMobile($builder, $value, $field)
    {
        return $builder->where($field, 'like', $value . '%');
    }

    public function filterNote($builder, $value, $field)
    {
        return $builder->where($field, 'like', $value . '%');
    }

    public static function insertIgnore($arrayOfArrays)
    {
        $static = new static();
        $table = with(new static)->getTable();
        $questionMarks = '';
        $values = [];
        if (empty($arrayOfArrays)) {
            return false;
        }

        foreach ($arrayOfArrays as $k => $array) {
            if ($static->timestamps) {
                $now = Carbon::now();
                if (!is_null(static::CREATED_AT)) {
                    $arrayOfArrays[$k]['created_at'] = $now;
                }
                if (!is_null(static::UPDATED_AT)) {
                    $arrayOfArrays[$k]['updated_at'] = $now;
                }
            }

            if ($k > 0) {
                $questionMarks .= ',';
            }

            $questionMarks .= '(?' . str_repeat(',?', count($array) - 1) . ')';
            $values = array_merge($values, array_values($array));
        }

        $fields = implode(',', array_map(function ($item) {
            return '`' . $item . '`';
        }, array_keys($array)));

        $query = "INSERT IGNORE INTO $table ($fields) VALUES $questionMarks";

        return DB::insert($query, $values);
    }

    public static function insertOrUpdate($rows)
    {
        $table = with(new static)->getTable();
        $first = reset($rows);
        $columns = implode(
            ',',
            array_map(function ($value) { return "`$value`"; }, array_keys($first))
        );

        $values = implode(
            ',',
            array_map(function ($row) {
                return '(' . implode(
                    ',',
                    array_map(function ($value) {
                        if (json_decode($value, true)) {
                            return '"' . str_replace('"', '\"', $value) . '"';
                        } else {
                            return '"' . str_replace('"', '""', $value) . '"';
                        }
                    }, $row)
                ) . ')';
            }, $rows)
        );

        $updates = implode(
            ',',
            array_map(function ($value) { return "`$value` = VALUES(`$value`)"; }, array_keys($first))
        );
        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

        return DB::statement($sql);
    }

    public function definedRelations($key = null): array
    {
        $reflector = new \ReflectionClass(get_called_class());

        return collect($reflector->getMethods())
            ->filter(
                function ($method) use ($key) {
                    if (!empty($method->getReturnType()) &&
                        (
                            (Str::contains($method->getReturnType(), 'Illuminate\Database\Eloquent\Relations') && empty($key)) ||
                            (Str::contains($method->getReturnType(), $key))
                        )
                    ) {
                        return $method;
                    }
                }
            )
            ->pluck('name')
            ->all();
    }
}
