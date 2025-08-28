<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use NodeTrait;

    public $timestamps = false;

    protected static $nodeTraitBooted = false;

    protected $default_sort_field = 'sort';

    protected $direction = 'ASC';

    protected $attributes = [
        'parent_id' => 0,
        'query'     => '',
        'params'    => ''
    ];

    protected $casts = [
        'hidden'     => 'boolean',
        'open_blank' => 'boolean',
    ];

    protected $columns = [
        'id',
        'title',
        'sub_title',
        'path',
        'icon',
        'acl',
        'sort',
        'query',
        'params',
        'hidden',
        'parent_id',
        '_lft',
        '_rgt'
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

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
}
