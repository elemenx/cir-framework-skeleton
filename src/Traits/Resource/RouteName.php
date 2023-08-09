<?php

namespace Elemenx\CirFrameworkSkeleton\Traits\Resource;

trait RouteName
{
    protected function names(): array
    {
        $default = property_exists($this, 'names') ? self::$names : [];

        $merge_names = array_map(fn ($name) => 'cir_framework_skeleton.' . $name, $default);

        return array_merge($default, $merge_names);
    }

    protected function isShowNames($as): bool
    {
        return property_exists($this, 'names') && (empty(self::$names)) || in_array($as, $this->names());
    }
}
