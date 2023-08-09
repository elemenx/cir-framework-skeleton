<?php

namespace Elemenx\CirFrameworkSkeleton;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class WhereQueryExpressionService
{
    private $expressionString;

    private $operators = [
        'gte'     => '>=',
        'gt'      => '>',
        'eq'      => '=',
        'ne'      => '!=',
        'lt'      => '<',
        'lte'     => '<=',
        'in'      => 'in',
        'notIn'   => 'not in',
        'like'    => 'like',
        'between' => 'between',
    ];

    public function __construct(string $expression_string)
    {
        $this->expressionString = $expression_string;
    }

    public function parse(): string
    {
        $expression = json_decode($this->expressionString, true);
        $sql = $this->expression($expression, $expression['operator']);

        return str_replace(' )', ')', $sql);
    }

    public function expression($data, $operator = 'and'): string
    {
        $parse_string = '';
        $type = Arr::get($data, 'type');
        $data_operator = Arr::get($data, 'operator');

        if ($type == 'wrap') {
            if (isset($data['children'])) {
                $parse_string = '(' . $this->expression($data['children'], $operator) . ')';
            }
        } elseif ($data_operator == 'and' || $data_operator == 'or') {
            if (isset($data['children'])) {
                $parse_string = '(' . $this->expression($data['children'], $data_operator) . ') ';
            }
        } else {
            $parse_string = $this->subExpression($data, $operator);
        }

        return $parse_string;
    }

    public function subExpression($children, $operator = 'and'): string
    {
        $sql = '';

        foreach ($children as $index => $child) {
            if (isset($child['formulas'])) {
                $formula = $child['formulas'];
                $field_type = Arr::get($formula, 'field_type');

                if ($this->isOperatorable($field_type)) {
                    $sql .= ($index != 0 ? $operator . ' ' : '') . sprintf(
                        '`%s` %s %s',
                        $formula['field'],
                        $this->operators[$field_type],
                        $this->convertValue($formula)
                    );
                }
            } else {
                $sql .= ($index != 0 ? $operator . ' ' : '') . $this->expression($child);
            }
        }

        return $sql;
    }

    public function convertValue($formula)
    {
        $field_type = Arr::get($formula, 'field_type');
        $value = Arr::get($formula, 'value');
        $value_type = Arr::get($formula, 'value_type');

        if ($field_type == 'notIn' || $field_type == 'in') {
            $value = '(' . $value . ')';
        } elseif ($field_type == 'between') {
            list($start, $end) = explode(',', $value);
            $start = Carbon::parse($start)->startOfDay();
            $end = Carbon::parse($end)->endOfDay();
            $value = "'$start' and '$end'";
        } elseif ($value_type == 'float') {
            $value = (float)$value;
        } elseif ($value_type == 'int') {
            $value = (int)$value;
        } elseif ($value_type == 'string') {
            $value = "'$value'";
        }

        return $value . ' ';
    }

    private function isOperatorable(string $field): bool
    {
        return array_key_exists($field, $this->operators);
    }
}
