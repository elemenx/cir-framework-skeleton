<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use Kalnoy\Nestedset\NodeTrait;

class Module extends Model
{
    use NodeTrait;

    public $timestamps = false;

    protected $default_sort_field = 'sequence';

    protected $direction = 'ASC';

    protected $casts = [
        'params' => 'array',
        'acls'   => 'array',
        'config' => 'array',
    ];

    protected $columns = [
        'id',
        'identifier',
        'type',
        'name',
        'acl',
        'params',
        'config',
        'data_resource_id',
        'sequence',
        'parent_identifier',
        'icon',
        '_lft',
        '_rgt',
        'parent_id',
    ];

    public function resources()
    {
        return $this->morphToMany(Resource::class, 'resourceable')->withPivot('identifier_alias');
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function selfResource()
    {
        return $this->resources()->limit(1);
    }

    public function dataResource()
    {
        return $this->belongsTo(Resource::class, 'data_resource_id');
    }

    public function settingCategories()
    {
        return $this->hasMany(SettingCategory::class, 'module_id');
    }

    public function settingItems()
    {
        return $this->hasMany(SettingItem::class, 'module_id');
    }
}
