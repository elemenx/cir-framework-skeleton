<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

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
        'config',
        'list_sequence',
        'search_sequence'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
