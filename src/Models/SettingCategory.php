<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use Elemenx\CirFrameworkSkeleton\Traits\Model\Uuid;

class SettingCategory extends Model
{
    use Uuid;

    public $timestamps = false;

    protected $default_sort_field = 'sequence';

    protected $direction = 'ASC';

    protected $casts = [
        'sequence' => 'integer'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'parent_id' => '00000000-0000-0000-0000-000000000000'
    ];

    protected $columns = [
        'id',
        'module_id',
        'parent_id',
        'key',
        'name',
        'sequence'
    ];

    public function parent()
    {
        return $this->belongsTo(SettingCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SettingCategory::class, 'parent_id');
    }

    public function items()
    {
        return $this->hasMany(SettingItem::class, 'parent_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
