<?php

namespace Elemenx\CirFrameworkSkeleton\Traits\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

trait Exportable
{
    protected $index;

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request|null $input
     */
    public function scopeExport(Builder $builder)
    {
        $collect = $builder->get();
        $this->index = 1;
        $data = [];
        $data[] = array_values($this->getExportTitle());

        foreach ($collect as $item) {
            $item_data = [];
            foreach ($this->getExportValue() as $field => $value) {
                $item_data[] = $this->convertValue($item, $field, $value);
            }
            $this->index++;
            $data[] = $item_data;
        }

        return $data;
    }

        /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request|null $input
     */
    public function scopeExcelExport(Builder $builder)
    {
        $as = app('request')->route()->getName();
        $explode_as = Arr::first(explode('.', $as));
        $classname = 'App\\Essential\\Exports\\' . Str::studly($explode_as) . 'Export';
        $class = new $classname($builder->get());

        return Excel::download($class, $explode_as . '.xlsx');
    }

    private function convertValue($item, $field, $value)
    {
        $return_value = '暂无';

        if ($value == 'index') {
            return $this->index;
        }

        if ($field === $value) {
            $return_value = $item->{$field};
        } elseif (is_array($value)) {
            $return_value = Arr::get($value, $item->{$field});
        } elseif (is_string($value)) {
            if ($this->isAttributeFunction($value)) {
                return $item->{lcfirst(Str::between($value, 'get', 'Attribute'))};
            }

            $values = [];
            $value_arr = explode('|', $value);

            foreach ($value_arr as $var) {
                $object = $item;

                foreach (explode('.', $var) as $explode_var_string) {
                    if (($object instanceof Model && $object->hasRelation($explode_var_string)) || !empty($object->{$explode_var_string})) {
                        $object = $object->{$explode_var_string};
                    } else {
                        $object = '暂无';
                        break;
                    }
                }

                if (!empty($object)) {
                    $values[] = $object;
                }
            }

            $return_value = implode('|', $values);
        }

        return $return_value;
    }

    protected function isAttributeFunction($value)
    {
        return Str::startsWith($value, 'get') && Str::endsWith($value, 'Attribute');
    }

    protected function getExportTitle()
    {
        return $this->getExportVar('title');
    }

    protected function getExportValue()
    {
        return $this->getExportVar('value');
    }

    private function getExportVar(string $str)
    {
        $as = app('request')->route()->getName();
        $as = str_replace('.', '_', $as);
        $field = 'export_' . $as . '_' . $str;

        return property_exists($this, $field) ? $this->{$field} : $this->{'export_' . $str};
    }
}
