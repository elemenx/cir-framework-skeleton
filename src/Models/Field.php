<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use Illuminate\Support\Arr;

class Field extends Model
{
    public $timestamps = false;

    protected $default_sort_field = 'list_sequence';

    protected $direction = 'ASC';

    protected $sorts = [
        'list_sequence',
        'search_sequence'
    ];

    protected $casts = [
        'list_sequence'   => 'integer',
        'search_sequence' => 'integer'
    ];

    protected $columns = [
        'id',
        'module_id',
        'name',
        'group_name',
        'config',
        'list_sequence',
        'search_sequence'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function getIdentifierAttribute()
    {
        return Arr::get(json_decode($this->config, true), 'identifier', $this->name);
    }
}
