<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class RoleStrategy extends Pivot
{
    protected $table = 'role_strategy';

    public $timestamps = false;

    protected $columns = [
        'id',
        'role_id',
        'strategy_id',
        'sequence'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }
}
