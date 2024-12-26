<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use NodeTrait;

    public $timestamps = false;

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

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
}
