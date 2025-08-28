<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use Kalnoy\Nestedset\NodeTrait;

class Module extends Model
{
    use NodeTrait;

    public $timestamps = false;

    protected static $nodeTraitBooted = false;

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
        'workflow_identifier',
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

    public static function bootNodeTrait()
    {
        // 防止在 Octane 模式下重复注册
        if (static::$nodeTraitBooted) {
            return;
        }

        static::$nodeTraitBooted = true;

        static::saving(function ($model) {
            return $model->callPendingAction();
        });

        static::deleting(function ($model) {
            // We will need fresh data to delete node safely
            $model->refreshNode();
        });

        static::deleted(function ($model) {
            $model->deleteDescendants();
        });

        if (static::usesSoftDelete()) {
            static::restoring(function ($model) {
                static::$deletedAt = $model->{$model->getDeletedAtColumn()};
            });

            static::restored(function ($model) {
                $model->restoreDescendants(static::$deletedAt);
            });
        }
    }

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

    public function workflow()
    {
        return $this->hasOne(Workflow::class, 'identifier', 'workflow_identifier');
    }
}
