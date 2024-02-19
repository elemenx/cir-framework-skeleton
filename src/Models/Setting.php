<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class Setting extends Model
{
    public $timestamps = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $primaryKey = 'key';

    protected $default_sort_field = 'key';

    protected $direction = 'ASC';

    protected $casts = [
        'value' => 'array',
    ];

    protected $columns = [
        'key',
        'value',
    ];
}
