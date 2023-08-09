<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use Elemenx\CirFrameworkSkeleton\Traits\Model\Uuid;

class SettingItem extends Model
{
    use Uuid;

    public $timestamps = false;

    protected $default_sort_field = 'sequence';

    protected $direction = 'ASC';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type_enabled' => true,
        'span'         => 24,
        'parent_id'    => '00000000-0000-0000-0000-000000000000'
    ];

    protected $casts = [
        'type_enabled' => 'boolean',
        'type_params'  => 'array',
        'span'         => 'integer',
        'sequence'     => 'integer'
    ];

    protected $columns = [
        'id',
        'module_id',
        'parent_id',
        'name',
        'key',
        'description',
        'help_link',
        'type',
        'type_params',
        'type_enabled',
        'data_source',
        'default_value',
        'span',
        'sequence'
    ];

    public function parent()
    {
        return $this->belongsTo(SettingCategory::class, 'parent_id', 'id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
