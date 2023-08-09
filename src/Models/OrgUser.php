<?php

namespace Elemenx\CirFrameworkSkeleton\Models;

class OrgUser extends Pivot
{
    public const UPDATED_AT = null;

    protected $casts = [
        'kitchen_ids' => 'array',
    ];

    protected $columns = [
        'id',
        'user_id',
        'org_id',
        'role_id',
        'kitchen_ids',
        'created_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
