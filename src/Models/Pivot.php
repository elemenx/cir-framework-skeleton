<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\Pivot as BaseModel;
use Illuminate\Support\Facades\DB;

class Pivot extends BaseModel
{
    protected $guarded = [];

    protected $columns = [];

    public function scopeExclude($query, ...$value)
    {
        return $query->select(array_diff($this->columns, $value));
    }

    public function getUpdatedAtColumn()
    {
        return null;
    }

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
}
